<?php 
function sendSms(){
	global $fnum, $message;
	
	$text =str_replace(' ', '+', $message);
 
//   $url = 'http://api.sms.bambika.co.ke:8555/?target=CHRISTA_NET&msisdn='.$fnum.'&text='.$text.'&login=christa_net&pass=d3be5132db85a1e8fae4a25555872339';
  
$new_url = "https://client.airtouch.co.ke:9012/sms/api/?issn=CHRISTA_NET&msisdn=$fnum&text=$text+api+message&username=christanet&password=100ab9a90f84414410c6f7e0c62a2346";

                $ch = curl_init();
				
                curl_setopt($ch, CURLOPT_URL, $new_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, $new_url);
				

                $output = curl_exec($ch);

                if ($output===FALSE) {
                     echo "cURL Error:". curl_error($ch);
                     
                }else{
					echo "sent";
				}

                curl_close($ch);

				
}

?>