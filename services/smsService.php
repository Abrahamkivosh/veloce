<?php

class smsService {
    private  $fnum, $text;
    public function __construct() {
    }

    private function sendSms() {
      $url =  "https://client.airtouch.co.ke:9012/sms/api/?issn=CHRISTA_NET&msisdn=$this->fnum&text=$this->text+api+message&username=christanet&password=100ab9a90f84414410c6f7e0c62a2346";
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $url);
        $output = curl_exec($ch);
        if ($output===FALSE) {
            echo "cURL Error:". curl_error($ch);
        }else{
            echo "sent";
        }
        curl_close($ch);
    }

    public function setPhone($fnum) {
        $this->fnum = $fnum;
    }
    public function setText($text) {
        // replace spaces with plus sign
        $text = str_replace(" ", "+", $text);
        $this->text = $text;
    }

    public function welcomeSMS($phone, $fullName, $accountNumber, $package, $userName, $password)
    {
        $message = "Dear $fullName, Welcome to Christa Networks Limited. Your account number is $accountNumber. Your plan is $package. Please use Mpesa Paybill No. 4043919 to complete your payment.To access your online portal use https://www.christanetworks.co.ke/selfcare Username - $userName Password - $password";   
        $this->setPhone($phone);
        $this->setText($message);
        // $this->sendSms();
    }
    public function renewalReminder ($phone, $fullName, $accountNumber,$package, $expiryDate)
    {
        $message = "Dear $fullName Your account $accountNumber will expire on $expiryDate. Please topup to avoid disconnection. Your plan is $package. Mpesa paybill: 4043919. Thank you. Christa Networks. To access your online portal use https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
        $this->sendSms();
    }

    public function confirmationOfPayment($phone, $newExpiryDate, $balance)
    {
        $message = "Your subscription has been renewed to $newExpiryDate. Your Wallet balance is Ksh.$balance. To
        access your online portal use  https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
        $this->sendSms();
    }

    public function disconnectionNotice($phone, $fullName, $accountNumber, $package)
    {
        $message = "Dear $fullName, Your account $accountNumber has been disconnected. Please topup to continue enjoying our services. Your plan is $package. Mpesa paybill: 4043919. Thank you. Christa Networks. To access your online portal use
        https://www.christanetworks.co.ke/selfcare";
        $this->setPhone($phone);
        $this->setText($message);
        $this->sendSms();
    }

    /**
     * Send When balance is less than  required amount to renew subscription
     */
    public function lessBalanceNotice($phone, $fullName, $accountNumber, $balance, $lessAmount)
    {
        $message = "Dear $fullName, Your account $accountNumber is less by KES $lessAmount to renew your subscription. Your balance is KES $balance.";
        $this->setPhone($phone);
        $this->setText($message);
        $this->sendSms();
    }

    public function test()
    {
        return "test Hello World";
    }

}