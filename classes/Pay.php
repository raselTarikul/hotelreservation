<?php
require 'payment/vendor/autoload.php';
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
class Pay {

    private $_mood = 'sandbox',
            $_timeout = 30,
            $_logEnabled = false,
            $_paymentMathod = 'paypal',
            $_shippingCost = 0.00,
            $_tax = 0.00,
            $_total,
            $_currency = 'USD',
            $_returUrl,
            $_cancellUrl,
            $_api;

    public  function __construct() {
        $this->_returUrl = Config::get('paypal/redirecturl');
        $this->_cancellUrl = Config::get('paypal/cancelurl');

        $authtoken = new OAuthTokenCredential(
                Config::get('paypal/clientid'), Config::get('paypal/secrectid')
        );

        $this->_api = new ApiContext($authtoken);
        $this->_api->setConfig([
            'mode' => $this->_mood,
            'http.ConnectionTimeOut' => $this->_timeout,
            'log.LogEnabled' => $this->_logEnabled
        ]);
    }

    public function pay($cost, $desctiption) {
        $payer = new Payer();
        $payer->setPaymentMethod($this->_paymentMathod);

        $details = new Details();
        $tax = ($this->_tax / 100) * $cost;
        $this->_total = $tax + $cost;
        $details->setTax("$tax")
                ->setShipping("$this->_shippingCost")
                ->setSubtotal("$this->_total");

        $amount = new Amount();
        $amount->setCurrency($this->_currency)
                ->setTotal("$this->_total")
                ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setDescription($desctiption);

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction]);

        $redirectUrl = new RedirectUrls();
        $redirectUrl->setReturnUrl($this->_returUrl)
                ->setCancelUrl($this->_cancellUrl);
        $payment->setRedirectUrls($redirectUrl);

        try {
            $payment->create($this->_api);
            Session::put(Config::get('session/paypal_token'), $payment->getId());
        } catch (Exception $ex) {
            echo $ex; 
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $approvalUrl = $link->getHref();
                break;
            }
        }
        header('Location: '.$approvalUrl);
       // echo "<a href='$approvalUrl' >$approvalUrl</a>";
    }
    
    public function execute($payerId, $paymentId){
        $payment = Payment::get($paymentId, $this->_api);
         $execution = new PaymentExecution();
          $execution->setPayerId($payerId);
          $payment->execute($execution, $this->_api);
    }

}
