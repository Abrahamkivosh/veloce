<?php
// import read env file
require_once  '../cpanel/library/read_env_file.php';


class smsService {
    private  $fnum, $text, $text_username, $text_password, $text_issn;
    public function __construct() {
        $this->text_username = getenv('TEXTUSERNAME');
        $this->text_password = getenv('TEXTPASSWORD');
        $this->text_issn = getenv('TEXT_ISSN');

    }

    private function sendSms() {
        $url = "https://client.airtouch.co.ke:9012/sms/api/?issn=$this->text_issn&msisdn=$this->fnum&text=$this->text&username=$this->text_username&password=$this->text_password";
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $url);
        $output = curl_exec($ch);
        if ($output===FALSE) {
            echo "cURL Error:". curl_error($ch);
            // Log error to file
            $error = "cURL Error:". curl_error($ch);
            $error_file = "../error.txt";
            $file = fopen ($error_file, "a");
            fwrite($file, $error);
            fclose($file);
            return $error;
        }else{
            return $output;
        }
        curl_close($ch);
    }

    public function setPhone($fnum) {
     
        // check if number start with 254
        $firstchar = $fnum[0];
        if ($firstchar == "0") {
            $newnum = substr($fnum, 1);
            $fnum = "254" . $newnum;
        } else {
            $fnum = $fnum;
        }
        // remove spaces
        $fnum = str_replace(" ", "", $fnum);
        // remove + sign
        $fnum = str_replace("+", "", $fnum);

        
        $this->fnum = $fnum;
    }
    public function setText($text) {
        // replace spaces with plus sign
        // $text = str_replace(" ", "+", $text);
        //  add url encoding
        $text = urlencode($text);
        $this->text = $text;
    }

    public function welcomeSMS($phone, $fullName, $accountNumber, $package, $userName, $password)
    {
        $message = "Dear $fullName, Welcome to Christa Networks Limited. Your account number is $accountNumber. Your plan is $package. Please use Mpesa Paybill No. 4043919 to complete your payment.To access your online portal use https://www.christanetworks.co.ke/selfcare Username - $userName Password - $password";   
        $this->setPhone($phone);
        $this->setText($message);
        $this->sendSms();
    }
    public function renewalReminder ($phone, $fullName, $accountNumber,$package, $expiryDate)
    {
        $message = "Dear $fullName Your account $accountNumber will expire on $expiryDate. Please topup to avoid disconnection. Your plan is $package. Mpesa paybill: 4043919. Thank you. Christa Networks. To access your online portal use https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
        return $this->sendSms();
    }

    public function confirmationOfPayment($phone, $newExpiryDate, $balance)
    {
        $message = "Your subscription has been renewed to $newExpiryDate. Your Wallet balance is Ksh.$balance. To access your online portal use  https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
      
        return $this->sendSms();
    }

    public function disconnectionNotice($phone, $fullName, $accountNumber, $package)
    {
        $message = "Dear $fullName, Your account $accountNumber has been disconnected. Please topup to continue enjoying our services. Your plan is $package. Mpesa paybill: 4043919. Thank you. Christa Networks. To access your online portal use
        https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
        return $this->sendSms();
    }

    /**
     * Send When balance is less than  required amount to renew subscription
     */
    public function lessBalanceNotice($phone, $fullName, $accountNumber, $balance, $lessAmount)
    {
        $message = "Dear $fullName, Your account $accountNumber is less by KES $lessAmount to renew your subscription. Your balance is KES $balance.";
        $this->setPhone($phone);
        $this->setText($message);
      
        return $this->sendSms();
    }

    public function sendPlainText($phone, $message)
    {
        $this->setPhone($phone);
        $this->setText($message);
        return $this->sendSms();
    }

}

// $smsService = new smsService();

// $response = $smsService->sendPlainText("254707585566", "Hello World");

// echo "<pre>";
// print_r($response);
// echo "</pre>";