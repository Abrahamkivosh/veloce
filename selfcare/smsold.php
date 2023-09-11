<?php 
function sendSms(){
error_reporting(E_ALL);
ini_set('display_errors', 1);
	global $fnum, $message;
	$uname = "christa";
	$Key = "diQJkjQMvsh89KOsDV7lpUfBt80kXbjLaBq8Sn7Ft0EGeJuR3X";
	$senderId = "SMARTLINK";
	$tophonenumber = $fnum; 
	$finalmessage = $message;
	$msgtype = 5;
	$dlr = 0;

 
  $url = 'https://sms.movesms.co.ke/api/compose?';
  $postData = array(
                    'action' => 'compose',
                    'username' => $uname,
                    'api_key' => $Key,
                    'sender' => $senderId,
                    'to' => $tophonenumber,
                    'message' => $finalmessage,
                    'msgtype' => $msgtype,
                    'dlr' => $dlr,
                );


                $ch = curl_init();
				
                curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

                $output = curl_exec($ch);

                if ($output===FALSE) {
                     echo "cURL Error:". curl_error($ch);
                   
                }

                curl_close($ch);

				print_r($output);
}

?>