<?php
function sendSms()
{
    global $fnum, $message;

    $text = str_replace(' ', '+', $message);

    $url = 'http://api.sms.bambika.co.ke:8555/?target=Veloce_NET&msisdn=' . $fnum . '&text=' . $text . '&login=Veloce_net&pass=d3be5132db85a1e8fae4a25555872339';



    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, $url);


    $output = curl_exec($ch);

    if ($output === FALSE) {
        echo "cURL Error:" . curl_error($ch);
    } else {
        echo "sent";
    }

    curl_close($ch);
}
