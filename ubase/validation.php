<?php
// Improved Payment Callback Handler
// Author: Abraham Kivosh

header("Content-Type: application/json");

require_once "../shared/database.php";

// Validate token
if (!isset($_GET["token"])) {
	echo json_encode(["error" => "Technical error. Token missing."]);
	exit();
}

$validToken = '217ab0d4de6d426df670759321392fc2';

if ($_GET["token"] !== $validToken) {
	echo json_encode(["error" => "Invalid authorization token."]);
	exit();
}

// Read and decode JSON input
$request = file_get_contents('php://input');
$array = json_decode($request, true);

if (json_last_error() !== JSON_ERROR_NONE) {
	echo json_encode(["error" => "Invalid JSON input."]);
	exit();
}

// Safely extract fields from input (with defaults)
$transactiontype = $array['TransactionType'] ?? '';
$transid = $array['TransID'] ?? '';
$transtime = $array['TransTime'] ?? '';
$transamount = $array['TransAmount'] ?? '';
$businessshortcode = $array['BusinessShortCode'] ?? '';
$billrefno = $array['BillRefNumber'] ?? '';
$invoiceno = $array['InvoiceNumber'] ?? '';
$msisdn = $array['MSISDN'] ?? '';
$orgaccountbalance = $array['OrgAccountBalance'] ?? '';
$firstname = $array['FirstName'] ?? '';
$middlename = $array['MiddleName'] ?? '';
$lastname = $array['LastName'] ?? '';

// Validate required field
if (empty($billrefno)) {
	echo json_encode(["ResultCode" => 1, "ResultDesc" => "Missing Bill Reference Number", "ThirdPartyTransID" => 0]);
	exit();
}

// Use prepared statement to prevent SQL injection
$acc = null;
$stmt = $con->prepare("SELECT account FROM userbillinfo WHERE account = ?");
$stmt->bind_param("s", $billrefno);
$stmt->execute();
$result = $stmt->get_result();
// dump result here



if ($row = $result->fetch_assoc()) {
	$acc = $row['account'];
}


// Generate response
if ((string) $acc === (string) $billrefno) {
	$response = [
		"ResultCode" => 0,
		"ResultDesc" => "Success",
		"ThirdPartyTransID" => 0
	];
} else {
	$response = [
		"ResultCode" => 1,
		"ResultDesc" => "Failed",
		"ThirdPartyTransID" => 0
	];
}

// Output the result as JSON
echo json_encode($response);

// Clean up
$stmt->close();
$con->close();
