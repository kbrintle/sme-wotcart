<?php

namespace common\models\billing;

require __DIR__  . '/vendor/autoload.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;


use common\components\CurrentStore;
use Yii;
use yii\helpers\Json;

class Paypal{
    private $current_store;
    private $current_store_settings;

    private $api_context;

    private $cc_type;
    private $cc_number;
    private $cc_month;
    private $cc_year;
    private $cc_cvc;
    private $cc_first_name;
    private $cc_last_name;

    private $price_shipping;
    private $price_tax;
    private $price_subtotal;



    /**
     * Paypal constructor.
     */
    public function __construct(){
        $this->setCurrentStore();
        $this->apiConfig();
//        $this->setCustomer($stripe_id);
    }



    private function setCurrentStore(){
        $this->current_store            = CurrentStore::getStore();
        $this->current_store_settings   = CurrentStore::getSettings();
    }


    /**
     * Creates Stripe API Connection based on
     */
    private function apiConfig(){
        if( $this->current_store_settings['payment']->paypal_enabled ){
            if( $this->current_store_settings['payment']->paypal_client_id
                && $this->current_store_settings['payment']->paypal_client_secret ) {
                $this->apiContext = new \PayPal\Rest\ApiContext(
                    new \PayPal\Auth\OAuthTokenCredential(
                        $this->current_store_settings['payment']->paypal_client_id,
                        $this->current_store_settings['payment']->paypal_client_secret
                    )
                );
            }
        }
    }

    public function createCharge($amount){

        //create payment
        $card = new PaymentCard();
        $card->setType($this->cc_type)
             ->setNumber($this->cc_number)
             ->setExpireMonth($this->cc_month)
             ->setExpireYear($this->cc_year)
             ->setCvv2($this->cc_cvc)
             ->setFirstName($this->cc_first_name)
             ->setLastName($this->cc_last_name)
             ->setBillingCountry("US");

        //create funding instrument
        $fi = new FundingInstrument();
        $fi->setPaymentCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        //set details
        $details = new Details();
        $details->setShipping($this->price_shipping)
            ->setTax($this->price_tax)
            ->setSubtotal($this->price_subtotal);

        //create amount
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($amount)
            ->setDetails($details);

        //create transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Payment description")
            ->setInvoiceNumber(uniqid());

        //create payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        //execute payment
        try{
            $payment->create($this->api_context);
        }catch (Exception $ex){
            return $ex;
        }

        return true;
    }

}