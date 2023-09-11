<?php
require 'db.php';
require 'sms.php';
require 'basic.php';
$now = time();
//echo $now;
$query1 = "SELECT DISTINCT username, planName, phone FROM userbillinfo";
$result = mysqli_query($con, $query1);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    $username = $row['username'];
    $planname = $row['planName'];
    $phone = $row['phone'];

    $firstchar = $phone[0];
    if ($firstchar == "0") {
        $newnum = substr($phone, 1);
        $fnum = "254" . $newnum;
    } else {
        $fnum = substr($phone, 1);
    }
    //echo($fnum)."</br>";

    $query2 = "SELECT firstname, lastname,account FROM userinfo WHERE username = '$username'";
    $result2 = mysqli_query($con, $query2);
    while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
        $fullname = $row['firstname'] . " " . $row['lastname'];
        $identity = $row['account'];
        //echo $fullname."</br>";
        //echo $identity."</br>";
    }
    $query3 = "SELECT value FROM radcheck WHERE username = '$username' AND attribute = 'expiration'";
    $result3 = mysqli_query($con, $query3);
    while ($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
        $acctime = strtotime($row['value']);
        $exptime = ($row['value']);
        //echo ($acctime)."</br>&nbsp&nbsp&nbsp";

    }

    $interval = round(($acctime - $now) / 86400);
    //echo $interval."</br>&nbsp&nbsp&nbsp";
    switch ($interval) {
        case "5":
            $message = "Dear {$fullname} Your account {$identity} will expire on {$exptime} Please topup to avoid disconnection.Your plan is {$planname}.Mpesa paybill: {$paybill}. Thank you. {$sitename}.";
            //echo"expiry"." =".$exptime."</br>";
            //echo $acctime."</br>";
            //echo $interval."</br>";
            //echo $message."</br>";
            sendSms();
            break;
        default:
            //echo "No message to send";
    }
}

mysqli_close($con);
