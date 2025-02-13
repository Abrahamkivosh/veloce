<?php

header("Content-Type: application/json");

// Include required files
require_once "../services/smsService.php";
require_once "../shared/database.php";

// Initialize SMS service
$smsService = new smsService();

// Authorization token check
$authToken = "217ab0d4de6d426df670759321392fc2";
if (!isset($_GET["token"]) || $_GET["token"] !== $authToken) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Get the input JSON data
$request = file_get_contents("php://input");
if (!$request) {
    echo json_encode(["error" => "Invalid input"]);
    exit();
}

// Decode JSON into an array
$data = json_decode($request, true);
if (!is_array($data)) {
    echo json_encode(["error" => "Invalid JSON format"]);
    exit();
}

// Extract and sanitize input data
$fields = [
    'TransactionType',
    'TransID',
    'TransTime',
    'TransAmount',
    'BusinessShortCode',
    'BillRefNumber',
    'InvoiceNumber',
    'MSISDN',
    'OrgAccountBalance',
    'FirstName',
    'MiddleName',
    'LastName'
];

foreach ($fields as $field) {
    $$field = isset($data[$field]) ? mysqli_real_escape_string($con, $data[$field]) : '';
}

// Insert transaction data into the database
$sql = "INSERT INTO mpesa_payments (TransactionType, TransID, TransTime, TransAmount, BusinessShortCode, BillRefNumber, InvoiceNumber, MSISDN, FirstName, MiddleName, LastName, OrgAccountBalance) 
        VALUES ('$TransactionType', '$TransID', '$TransTime', '$TransAmount', '$BusinessShortCode', '$BillRefNumber', '$InvoiceNumber', '$MSISDN', '$FirstName', '$MiddleName', '$LastName', '$OrgAccountBalance')";
$response = mysqli_query($con, $sql);

// I need to stocre $data data, $sql and $response to a file
// Log the transaction data
$transactionData = json_encode($data);
$transactionSql = $sql;
$transactionResponse = $response ? "Success" : "Failed";

$transactionLog = "Transaction Data: $transactionData\nSQL Query: $transactionSql\nResponse: $transactionResponse\n\n";
$file = "transaction.log";
// check if file exists
if (file_exists($file)) {
    // write to file
    file_put_contents($file, $transactionLog, FILE_APPEND);
} else {
    // create file and write to it
    file_put_contents($file, $transactionLog);
}



// Fetch user details based on BillRefNumber
$userQuery = "SELECT account, username, firstname, lastname FROM userinfo WHERE account = '$BillRefNumber'";
$userResult = mysqli_query($con, $userQuery);
$user = mysqli_fetch_assoc($userResult);

if (!$user) {
    echo json_encode(["error" => "User not found"]);
    exit();
}

$username = $user['username'];
$fullname = "{$user['firstname']} {$user['lastname']}";

// Fetch user billing info
$billingQuery = "SELECT planName, account, phone, balance FROM userbillinfo WHERE username = '$username'";
$billingResult = mysqli_query($con, $billingQuery);
$billingInfo = mysqli_fetch_assoc($billingResult);

if (!$billingInfo) {
    echo json_encode(["error" => "Billing information not found"]);
    exit();
}



$plan = $billingInfo['planName'];
$phone = $billingInfo['phone'];
$account = $billingInfo['account'];
$balance = $billingInfo['balance'] ?? 0;

// Fetch plan cost and time bank
$planQuery = "SELECT planCost, planTimeBank FROM billing_plans WHERE planName = '$plan'";
$planResult = mysqli_query($con, $planQuery);
$planInfo = mysqli_fetch_assoc($planResult);

if (!$planInfo) {
    echo json_encode(["error" => "Plan details not found"]);
    exit();
}

$planCost = $planInfo['planCost'];
$timeBank = $planInfo['planTimeBank'];

// Fetch expiration date
$expirationQuery = "SELECT value FROM radcheck WHERE username = '$username' AND attribute = 'Expiration'";
$expirationResult = mysqli_query($con, $expirationQuery);
$expirationData = mysqli_fetch_assoc($expirationResult);

if ($expirationData) {
    $expiryTimestamp = strtotime($expirationData['value']);
    $currentTimestamp = time();
    $newTimestamp = max($currentTimestamp, $expiryTimestamp) + $timeBank;
    $newExpiryDate = date("d M Y H:i:s", $newTimestamp);
} else {
    echo json_encode(["error" => "Expiration data not found"]);
    exit();
}

// Process payment and update user balance
if ($TransAmount >= $planCost) {
    $newBalance = ($TransAmount - $planCost) + $balance;

    mysqli_query($con, "UPDATE radcheck SET value='$newExpiryDate' WHERE username='$username' AND attribute='Expiration'");
    mysqli_query($con, "UPDATE userbillinfo SET balance='$newBalance' WHERE username='$username'");

    $smsService->confirmationOfPayment($phone, $newExpiryDate, $newBalance);
} else {
    $totalAmount = $TransAmount + $balance;



    if ($totalAmount >= $planCost) {
        $newBalance = $totalAmount - $planCost;

        mysqli_query($con, "UPDATE radcheck SET value='$newExpiryDate' WHERE username='$username' AND attribute='Expiration'");
        mysqli_query($con, "UPDATE userbillinfo SET balance='$newBalance' WHERE username='$username'");

        $smsService->confirmationOfPayment($phone, $newExpiryDate, $newBalance);
    } else {
        $remainingAmount = $planCost - $totalAmount;

        mysqli_query($con, "UPDATE userbillinfo SET balance='$totalAmount' WHERE username='$username'");

        $smsService->lessBalanceNotice($phone, $fullname, $account, $totalAmount, $remainingAmount);
    }
}

// Return success response
echo json_encode(["ResultCode" => 0, "ResultDesc" => "Confirmation received successfully"]);

// Close the database connection
mysqli_close($con);
