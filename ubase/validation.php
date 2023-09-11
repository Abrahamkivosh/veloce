<?php 
header("Content-Type:application/json"); 

if (!isset($_GET["token"]))
{
echo "Technical error";
exit();
}


if ($_GET["token"]!='217ab0d4de6d426df670759321392fc2')
{
echo "Invalid authorization";
exit();
}


$servername = 'localhost';
$username = 'root';
$password = '@christanetworks7879';
$dbname = 'radius';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());
}

$request=file_get_contents('php://input');



$array = json_decode($request, true);
$transactiontype= mysqli_real_escape_string($con,$array['TransactionType']); 
$transid=mysqli_real_escape_string($con,$array['TransID']); 
$transtime= mysqli_real_escape_string($con,$array['TransTime']); 
$transamount= mysqli_real_escape_string($con,$array['TransAmount']); 
$businessshortcode=  mysqli_real_escape_string($con,$array['BusinessShortCode']); 
$billrefno=  mysqli_real_escape_string($con,$array['BillRefNumber']); 
$invoiceno=  mysqli_real_escape_string($con,$array['InvoiceNumber']); 
$msisdn=  mysqli_real_escape_string($con,$array['MSISDN']); 
$orgaccountbalance=   mysqli_real_escape_string($con,$array['OrgAccountBalance']); 
$firstname=mysqli_real_escape_string($con,$array['FirstName']); 
$middlename=mysqli_real_escape_string($con,$array['MiddleName']); 
$lastname=mysqli_real_escape_string($con,$array['LastName']); 



$sqli = "SELECT * FROM userbillinfo WHERE account ='$billrefno'";
										$result = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											$acc = ($row['account']);
																					
										}

/* 
here you need to parse the json format 
and do your business logic e.g. 
you can use the Bill Reference number 
or mobile phone of a customer 
to search for a matching record on your database. 
*/ 

/* 
Reject an Mpesa transaction 
by replying with the below code 
*/ 

if ($acc!== $billrefno){
	
	
	echo '{"ResultCode":1, "ResultDesc":"Failed", "ThirdPartyTransID": 0}';
	
}
else{
	echo '{"ResultCode":0, "ResultDesc":"Success", "ThirdPartyTransID": 0}';
}

mysqli_close($con);
/* 
Accept an Mpesa transaction 
by replying with the below code 
*/ 


 
?>