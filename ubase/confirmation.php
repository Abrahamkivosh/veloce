<?php

header("Content-Type:application/json");
// include smsService
require_once "../services/smsService.php";

$smsService = new smsService();

// inclue database file in shared folder
require_once "../shared/database.php";

if (!isset($_GET["token"])) {
    echo "Technical error";
    exit();
}

if ($_GET["token"] != '217ab0d4de6d426df670759321392fc2') {
    echo "Invalid authorization";
    exit();
}

if (!$request = file_get_contents('php://input')) {
    echo "Invalid input";
    exit();
}

//Put the json string that we received from Safaricom to an array
$array = json_decode($request, true);
$transactiontype = mysqli_real_escape_string($con, $array['TransactionType']);
$transid = mysqli_real_escape_string($con, $array['TransID']);
$transtime = mysqli_real_escape_string($con, $array['TransTime']);
$transamount = mysqli_real_escape_string($con, $array['TransAmount']);
$businessshortcode = mysqli_real_escape_string($con, $array['BusinessShortCode']);
$billrefno = mysqli_real_escape_string($con, $array['BillRefNumber']);
$invoiceno = mysqli_real_escape_string($con, $array['InvoiceNumber']);
$msisdn = mysqli_real_escape_string($con, $array['MSISDN']);
$orgaccountbalance = mysqli_real_escape_string($con, $array['OrgAccountBalance']);
$firstname = mysqli_real_escape_string($con, $array['FirstName']);
$middlename = mysqli_real_escape_string($con, $array['MiddleName']);
$lastname = mysqli_real_escape_string($con, $array['LastName']);

$sql = "INSERT INTO mpesa_payments
(
TransactionType,
TransID,
TransTime,
TransAmount,
BusinessShortCode,
BillRefNumber,
InvoiceNumber,
MSISDN,
FirstName,
MiddleName,
LastName,
OrgAccountBalance
)
VALUES
(
'$transactiontype',
'$transid',
'$transtime',
'$transamount',
'$businessshortcode',
'$billrefno',
'$invoiceno',
'$msisdn',
'$firstname',
'$middlename',
'$lastname',
'$orgaccountbalance'
)";

$sqlis = "SELECT account,username,firstname,lastname FROM userinfo WHERE account = '$billrefno'";
$resulte = mysqli_query($con, $sqlis);
$fullname = "";
$username = "";
// return $resulte;
while ($row = mysqli_fetch_array($resulte, MYSQLI_ASSOC)) {
    # code...
    $username = ($row['username']);
    $fullname = ($row['firstname'] . " " . $row['lastname']);
}

$phone = "";

$sq = "SELECT * FROM userbillinfo WHERE username = '$username'";

$result = mysqli_query($con, $sq);
while ($row = mysqli_fetch_array($result)) {
    $plan = ($row['planName']);
    $account = ($row['account']);
    $phone = ($row['phone']);
}


$sqli2 = "SELECT * FROM billing_plans WHERE planName = '$plan'";
$result4 = mysqli_query($con, $sqli2);
while ($row = mysqli_fetch_array($result4)) {
    $plancost = ($row['planCost']);
    $timebank = ($row['planTimeBank']);
}
$sqli = "SELECT * FROM userbillinfo WHERE username = '$username'";
$result = mysqli_query($con, $sqli);
while ($row = mysqli_fetch_array($result)) {
    # code...
    if (($row['balance']) > 0) {
        $plan = ($row['planName']);
        $account = ($row['account']);
        $balance = ($row['balance']);
    } else {

        $account = ($row['account']);
        $balance = ($row['balance']);
        $balance = 0;
    }
}

$sqli3 = "SELECT * FROM radcheck WHERE ((username = '$username')&&(attribute = 'Expiration'))";
$result3 = mysqli_query($con, $sqli3);
while ($row = mysqli_fetch_array($result3)) {
    # code...
    $rexp = ($row['value']);
    $eXpiry = strtotime($row['value']);
    $timenow = time();

    if ($timenow > $eXpiry) {
        $timenew = (($eXpiry + ($timenow - $eXpiry)) + ($timebank));
        $newdate = date("d M Y H:i:s", $timenew);
        //echo $newdate."</br>";

    } else {
        $timenew = $eXpiry + $timebank;
        $newdate = date("d M Y H:i:s", $timenew);
        //echo $newdate."</br>";
    }
}

if ($transamount > $plancost) {

    $bal = ($transamount - $plancost);

    $new_balance = ($bal + $balance);

    $sqli4 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
    $result10 = mysqli_query($con, $sqli4);
    $sqli5 = "UPDATE userbillinfo SET balance='$new_balance' WHERE username='$username'";
    $result11 = mysqli_query($con, $sqli5);

    $response = $smsService->confirmationOfPayment($phone, $newdate, $new_balance);
} else if ($transamount < $plancost) {

    $new_amount = ($transamount + $balance);

    if ($new_amount >= $plancost) {

        $new_balance = $new_amount - $plancost;

        $sqli7 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
        $result12 = mysqli_query($con, $sqli7);
        $sqli8 = "UPDATE userbillinfo SET balance='$new_balance' WHERE username='$username'";
        $result13 = mysqli_query($con, $sqli8);
        $smsService->confirmationOfPayment($phone, $newdate, $new_balance);
    } else {
        $diff = $plancost - $new_amount;
        $sqli9 = "UPDATE userbillinfo SET balance='$new_amount' WHERE username='$username'";
        $result14 = mysqli_query($con, $sqli9);

        $smsService->lessBalanceNotice($phone, $fullname, $account, $new_amount, $diff);
    }
} else {
    $sqli11 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
    $result16 = mysqli_query($con, $sqli11);

    $smsService->confirmationOfPayment($phone, $newdate, $balance);
}

if (!mysqli_query($con, $sql)) {
    echo mysqli_error($con);
} else {
    echo '{"ResultCode":0,"ResultDesc":"Confirmation received successfully"}';
}

mysqli_close($con);
