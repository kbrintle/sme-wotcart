<?php

namespace common\models\billing;

use common\components\CurrentStore;
use Yii;
use yii\helpers\Json;

class Stripe{

    private $stripe_customer;
    private $stripe_token;

    private $current_store;
    private $current_store_settings;


    /**
     * Configure Stripe API Connection and retrieve Stripe Customer
     *
     * @param $stripe_id
     */
    public function __construct($stripe_token){
        $this->setCurrentStore();
        $this->apiConfig();
        $this->stripe_token = $stripe_token;
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
        if( $this->current_store_settings['payment']->stripe_enabled ){
            $api_key = $this->current_store_settings['payment']->stripe_live_secret_key;
            if( $this->current_store_settings['payment']->stripe_test_mode ){
                $api_key = $this->current_store_settings['payment']->stripe_test_secret_key;
            }
        }
        \Stripe\Stripe::setApiKey($api_key);
    }




    /**
     * Retrieve Stripe Customer record from provided Stripe Customer ID
     *
     * @param $stripe_id
     */
    private function setCustomer($stripe_id){
        $this->stripe_customer = \Stripe\Customer::retrieve($stripe_id);
    }


    /**
     * Create a charge based on an integer in cents (123 = $1.23)
     *
     * @param $amount
     */
    public function createCharge($amount){
        \Stripe\Charge::create(array(
            "amount"        => $amount,
            "currency"      => "usd",
            "source"        => $this->stripe_token, // obtained with Stripe.js
            "description"   => "Charge for " .Yii::$app->name ." - " . $this->current_store->name
        ));
    }


}