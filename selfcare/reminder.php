<?php


require 'basic.php';
require_once "../services/smsService.php";

// inclue database file in shared folder
require_once "../shared/database.php";


$smsService = new smsService();

$now = time();
$query1 = "SELECT DISTINCT username, planName, phone FROM userbillinfo";
$result = mysqli_query($con, $query1);


while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    $username = $row['username'];
    $planname = $row['planName'];
    $phone = $row['phone'];

    $query2 = "SELECT firstname, lastname,account FROM userinfo WHERE username = '$username'";
    $result2 = mysqli_query($con, $query2);
    while ($row = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
        $fullname = $row['firstname'] . " " . $row['lastname'];
        $identity = $row['account'];
        $query3 = "SELECT value FROM radcheck WHERE username = '$username' AND attribute = 'expiration'";
        $result3 = mysqli_query($con, $query3);
        while ($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
            $acctime = strtotime($row['value']);
            $exptime = ($row['value']);
        }

        $interval = round(($acctime - $now) / 86400);
        $interval = intval(abs($interval));

        // continue if $identity  is not equal to 1145
        if ($identity !== (string) 1145) {
            continue;
        }

        switch ($interval) {
            case 5:
                $smsService->renewalReminder($phone, $fullname, $identity, $planname, $exptime);
                break;
            case 3:
                $smsService->renewalReminder($phone, $fullname, $identity, $planname, $exptime);
                break;
            case 2:
                $smsService->renewalReminder($phone, $fullname, $identity, $planname, $exptime);
                break;
            default:
                break;
        }
    }
}

mysqli_close($con);
