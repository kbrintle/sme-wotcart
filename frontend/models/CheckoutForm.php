<?php

namespace frontend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogProduct;
use common\models\core\CoreConfig;
use common\models\core\CountryRegion;
use common\models\core\Subregions;
use common\models\customer\Customer;
use common\models\customer\CustomerAddress;
use common\models\customer\CustomerReward;
use common\models\sales\SalesOrder;
use common\models\sales\SalesOrderAddress;
use common\models\sales\SalesOrderItem;
use common\models\sales\SalesOrderPayment;
use common\models\sales\SalesOrderStatus;
use common\models\sales\SalesQuote;
use common\models\sales\SalesQuoteItem;
use frontend\controllers\CartController;
use common\models\billing\CardConnectRestClient;
use backend\components\CurrentUser;

use Yii;
use yii\base\Model;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;

class CheckoutForm extends Model
{
    private $customer_user;
    private $customer_email;
    public $customer_guest_email;
    public $customer_user_id;
    public $customer_note;

    public $shipping_first_name;
    public $shipping_last_name;
    public $shipping_street_address;
    public $shipping_apartment_suite;
    public $shipping_city;
    public $shipping_region_id;
    public $shipping_subregion_id;
    public $shipping_zipcode;
    public $shipping_phone;

    public $billing_is_shipping;

    public $billing_first_name;
    public $billing_last_name;
    public $billing_street_address;
    public $billing_apartment_suite;
    public $billing_city;
    public $billing_region_id;
    public $billing_subregion_id;
    public $billing_zipcode;
    public $billing_phone;
    public $stripe_token;

    public $paypal_card_first_name; //DO NOT SAVE THIS ANYWHERE
    public $paypal_card_last_name;  //DO NOT SAVE THIS ANYWHERE
    public $paypal_card_number;     //DO NOT SAVE THIS ANYWHERE
    public $paypal_exp_month;       //DO NOT SAVE THIS ANYWHERE
    public $paypal_exp_year;        //DO NOT SAVE THIS ANYWHERE
    public $paypal_cvc;             //DO NOT SAVE THIS ANYWHERE


    public $card_name; //DO NOT SAVE THIS ANYWHERE
    public $card_number;     //DO NOT SAVE THIS ANYWHERE
    public $card_exp_month;       //DO NOT SAVE THIS ANYWHERE
    public $card_exp_year;        //DO NOT SAVE THIS ANYWHERE
    public $card_cvc;             //DO NOT SAVE THIS ANYWHERE

    public $delivery_method = "";
    public $purchase_order;
    public $reward_points;
    public $cart;
    private $payment_settings;


    /**
     * Tracking variables
     */
    public $order;
    private $order_address_billing;
    private $order_address_shipping;
    private $order_items = [];


    const CARD_CONNECT_MERCHANT = '496160873888';
    const CARD_CONNECT_URL = 'https://fts.cardconnect.com:6443/cardconnect/rest';
    const CARD_CONNECT_USER = 'testing';
    const CARD_CONNECT_PASS = 'testing123';
    const CARD_CONNECT_MERCHANT_PROD = '510159080704612';
    const CARD_CONNECT_URL_PROD = 'https://fts.cardconnect.com:6443/cardconnect/rest';
    const CARD_CONNECT_USER_PROD = 'medicare';
    const CARD_CONNECT_PASS_PROD = 'H!Bj2zj@K4uLY#4KD5J2';

    public function init()
    {
        $this->setCustomer();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shipping_first_name',
                'shipping_last_name',
                'shipping_street_address',
                'shipping_apartment_suite',
                'shipping_city',
                'shipping_subregion_id',
                'billing_first_name',
                'billing_last_name',
                'billing_street_address',
                'billing_apartment_suite',
                'billing_city',
                'billing_subregion_id',
                'stripe_token',
                'customer_guest_email',
                'paypal_card_first_name',
                'card_name',
                'purchase_order',
                'shipping_phone',
                'billing_phone',
                'shipping_zipcode',
                'billing_zipcode',
                'paypal_card_last_name'], 'string'],
            [[
                'card_number',
                'card_exp_month',
                'card_exp_year',
                'card_cvc',
                'paypal_card_number',
                'paypal_exp_month',
                'paypal_exp_year',
                'paypal_cvc', 'reward_points'], 'integer'],
            [['billing_is_shipping'], 'boolean'],
            [['shipping_first_name',
                'shipping_last_name',
                'shipping_street_address',
                'shipping_city',
                'shipping_subregion_id',
                'shipping_zipcode',
                'billing_first_name',
                'billing_last_name',
                'billing_street_address',
                'billing_city',
                'billing_subregion_id',
                'billing_zipcode',]
                , 'required'],

//            [['stripe_token'], 'required', 'when' => function($model){
//                return $this->paymentSettings['stripe_enabled'];
//            }],
//
//            [['paypal_card_first_name', 'paypal_card_last_name', 'paypal_card_number', 'paypal_exp_month', 'paypal_exp_year', 'paypal_cvc'], 'required', 'when' => function($model){
//                return $this->paymentSettings['paypal_enabled'];
//            }],

//            [['billing_zipcode', 'shipping_zipcode'], 'is5NumbersOnly'],
            ['customer_note', 'safe'],
            //[['reward_points'], 'validateRewards', 'skipOnEmpty' => false, 'skipOnError' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'shipping_first_name' => 'First Name',
            'shipping_last_name' => 'Last Name',
            'shipping_street_address' => 'Street Address',
            'shipping_apartment_suite' => 'Apartment/Suite #',
            'shipping_city' => 'City',
            'shipping_subregion_id' => 'State',
            'shipping_zipcode' => 'Zipcode',
            'shipping_phone' => 'Phone',
            'billing_is_shipping' => 'Shipping address is same as billing',
            'billing_first_name' => 'First Name',
            'billing_last_name' => 'Last Name',
            'billing_street_address' => 'Street Address',
            'billing_apartment_suite' => 'Apartment/Suite #',
            'billing_city' => 'City',
            'billing_subregion_id' => 'State',
            'billing_zipcode' => 'Zipcode',
            'billing_phone' => 'Phone',
            'delivery_method' => 'Delivery Method',
            'customer_login_email' => 'Email',
            'customer_login_password' => 'Password',
            'customer_guest_email' => 'Email',
            'paypal_card_first_name' => 'Card First Name',
            'paypal_card_last_name' => 'Card Last Name',
            'paypal_card_number' => 'Card Number',
            'paypal_exp_month' => 'MM',
            'paypal_exp_year' => 'YY',
            'paypal_cvc' => 'CVC',
            'card_name' => 'Card First Name',
            'card_number' => 'Card Number',
            'card_exp_month' => 'MM',
            'card_exp_year' => 'YY',
            'card_cvc' => 'CVC'
        ];
    }


    /** =====
     * Validator functions START
     * ===== */
    public function is5NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{5}$/', $this->$attribute)) {
            $this->addError($attribute, 'must contain exactly 5 digits');
        }
    }

//    public function validateZipCode($attribute)
//    {
//        $test_zip_code = $this->billing_zipcode;
//        $current_store_id = CurrentStore::getStoreId();
//
//        if ($current_store_id
//            && $current_store_id != 1) {    //validate if current store is not National
//            //get store zipcodes
//            $connection = Yii::$app->getDb();
//            $command = $connection->createCommand("
//              SELECT zip_code
//              FROM `store_zip_code`
//              WHERE `store_id` = $current_store_id
//            ");
//            $store_zip_codes = ArrayHelper::getColumn($command->queryAll(), 'zip_code');
//
//            if (in_array($test_zip_code, $store_zip_codes)) {
//                //zip_code is valid for current store, continue
//            } else {
//                $this->addError($attribute, "Your Zip Code is not part of this store's region");
//            }
//        }
//
//    }

    public function validateRewards($attribute)
    {
        if (CustomerReward::getUsablePoints(Yii::$app->user->id) < $this->reward_points) {
            $this->addError($attribute, "You dont have that many points to spend");
            return false;
        }
    }

    public function checkPurchaseOrderOnOff($attribute)
    {
        if (!isset($this->paymentSettings) || !isset($this->paymentSettings->purchase_order_enabled)) {
            $this->addError($attribute, "This store has not been set up for Purchase Orders");
            return false;
        }
        if ($this->paymentSettings->purchase_order_enabled == true && strlen($this->purchase_order) == 0) {
            $this->addError($attribute, "Purchase Order cannot be blank");
            return false;
        }
    }

    /** =====
     * Validator functions END
     * ===== */


    /** =====
     * Get functions START
     * ===== */
    public function getPaymentSettings()
    {
        return CurrentStore::getStore()->paymentSettings;
    }

    public function getStates()
    {
        return ArrayHelper::map(CountryRegion::find()->where(['country_id' => 'US'])->all(), 'id', 'code');
    }
    /** =====
     * Get functions END
     * ===== */


    /** =====
     * Set functions START
     * ===== */
    /**
     * Now sets shipping same as billing
     */
    public function setBillingAsShipping()
    {
        if ($this->billing_is_shipping) {
            $keys = get_object_vars($this);
            $shipping_keys = [];
            foreach ($keys as $k => $v) {
                if (strpos($k, 'shipping_') !== false) {
                    array_push($shipping_keys, str_replace('shipping_', '', $k));
                }
            }

            foreach ($shipping_keys as $key) {
                if ($key != 'is_billing') {
                    $this->{"billing_$key"} = $this->{"shipping_$key"};
                }
            }
        }
    }

    public function setCustomer($guest_email = null)
    {
        if (!Yii::$app->user->isGuest) {
            $this->customer_user = Yii::$app->user->identity;
            return $this->customer_user_id = Yii::$app->user->id;
        } elseif ($guest_email) {
            return $this->customer_guest_email = $guest_email;
        }
        return null;
    }

    /** =====
     * Set functions END
     * ===== */


    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->customer_login_password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    public function getUser()
    {
        if ($this->customer_user === null) {
            $this->customer_user = Customer::findByUsername($this->customer_login_email);
        }
        return $this->customer_user;
    }

    public function validateUser()
    {
        if ($this->customer_user_id) {
            $this->customer_email = $this->customer_user->email;
            return $this->customer_email;
        } elseif ($this->customer_guest_email) {
            $this->customer_email = $this->customer_guest_email;
            return $this->customer_email;
        }

        return null;
    }


    /** =====
     * Create order functions START
     * ===== */
    public function createOrder()
    {
        if ($this->order = $this->createSalesOrder()) {    //create sales_order

            //create sales_order_address (for some reason order_address_shipping keeps coming out to a boolean so this is a compromise)
            $this->order_address_billing = $this->createSalesOrderAddress('billing');
            $this->order_address_shipping = $this->createSalesOrderAddress('shipping');
            if ($this->order_address_billing
                && $this->order_address_shipping) {
                //update the sales_order with sales_order_address results
                $this->order->shipping_address_id = $this->order_address_shipping->id;
                $this->order->billing_address_id = $this->order_address_billing->id;
                if ($this->order->save()) {
                    //create sales_order_item and add it to global order_items array
                    $line_items = SalesQuote::getQuoteItems();

                    foreach ($line_items as $line_item) {
                        if ($sales_order_item = $this->createSalesOrderItem($line_item)) {
                            array_push($this->order_items, $sales_order_item);
                        } else {
                            return false;
                        }
                    }

                    //if no errors, then we made it all the way through
                    return true;
                }
            }
        }
    }

    private function createSalesOrder()
    {
        $order = new SalesOrder();

        $cartSubTotal = SalesOrder::calculateSubtotal();
        $recalculatedSubtotal = SalesOrder::calculateLowestPriceSubtotal();
        $subTotal = ($cartSubTotal > $recalculatedSubtotal) ? $recalculatedSubtotal : $cartSubTotal;
        $order->subtotal = $subTotal;

        $discount_value = 0;
        if (CartController::getPromoDiscount()) {
            $order->coupon_code = CartController::getPromoCode();
            $discount_value = CartController::getPromoDiscount();
            $order->discount_amount = $discount_value;
        }

        $rewardValue = SalesQuote::getRewardPointsValue();
        $rewardValue = ($rewardValue > $subTotal) ? $subTotal : $rewardValue;

        $discounts = $rewardValue + $discount_value;
        if ($discounts > $subTotal) {
            $discounts = $subTotal;
        }
        $order->discount_reward_amount = $rewardValue;
        $subTotal -= $discounts;
        $order->subtotal_incl_discounts = $subTotal;
        $order->store_id = CurrentStore::getStoreId();
        if (CurrentStore::getStore()->name == "SME" || CurrentStore::getStore()->name == "Gold") {
            $order->status = SalesOrderStatus::PENDING;
        } else {
            $order->status = SalesOrderStatus::ON_HOLD;
            //find supervisor of that store and email them
        }
        $order->subtotal_incl_tax = SalesOrder::calculateSubtotalInclTax($subTotal);
        $order->tax_amount = SalesOrder::calculateTaxAmount($subTotal);
        $order->grand_total = SalesOrder::calculateTotal($subTotal);
        $order->total_paid = SalesOrder::calculateTotalPaid();
        $order->shipping_method = $this->delivery_method;
        $order->purchase_order = $this->purchase_order;
        $order->shipping_amount = SalesOrder::calculateShippingAmount($subTotal);
        $order->customer_id = Yii::$app->user->identity ? Yii::$app->user->id : NULL;
        $order->customer_email = Yii::$app->user->identity ? Yii::$app->user->identity->email : $this->customer_email;
        $order->customer_firstname = Yii::$app->user->identity ? Yii::$app->user->identity->first_name : $this->billing_first_name;
        $order->customer_lastname = Yii::$app->user->identity ? Yii::$app->user->identity->last_name : $this->billing_last_name;
        $order->store_name = CurrentStore::getStore() ? CurrentStore::getStore()->name : '';
        $order->remote_ip = Yii::$app->request->userIP;
        $order->total_item_count = $order->getItemsCount();
        $order->created_at = time();
        $order->updated_at = time();
        $order->customer_note = $this->customer_note;

        if (!$order->save()) {
            \Yii::$app->getSession()->setFlash('error', "There was an error when creating Sales Order");
            if (YII_ENV_DEV)
                VarDumper::dump($order->errors, 10, true);
            return null;
        }

        return $order;
    }

    private function createSalesOrderAddress($type)
    {
        $order_address = new SalesOrderAddress();
        $order_address->customer_id = $this->order->customer_id;
        $order_address->address_type = $type;
        $order_address->firstname = $this->{$type . "_first_name"};
        $order_address->lastname = $this->{$type . "_last_name"};
        $order_address->postcode = $this->{$type . "_zipcode"};
        $order_address->street = $this->{$type . "_street_address"};
        $order_address->street2 = isset($this->{$type . "_apartment_suite"}) ? $this->{$type . "_apartment_suite"} : NULL;
        $order_address->city = $this->{$type . "_city"};
        $order_address->region_id = $this->{$type . "_subregion_id"};
        $order_address->subregion_id = $this->{$type . "_subregion_id"};
        $order_address->email = $this->customer_email;
        $order_address->telephone = $this->{$type . "_phone"};
        if (!$order_address->save()) {
            \Yii::$app->getSession()->setFlash('error', "There was an error when creating $type address");
            if (YII_ENV_DEV)
                VarDumper::dump($order_address->errors, 10, true);
            return null;
        }

        return $order_address;
    }

    private function createSalesOrderItem($line_item)
    {
        $order_item = new SalesOrderItem();
        $order_item->order_id = $this->order->id;
        $order_item->store_id = $this->order->store_id;
        $order_item->product_id = $line_item->product_id;
        $order_item->qty_ordered = $line_item->qty;
        $order_item->name = $line_item->name;
        $order_item->sku = $line_item->sku;
        $order_item->product_type = CatalogProduct::getAttributeSet($line_item->product_id);
        $order_item->price = $line_item->price;
        $order_item->subtotal = $line_item->price * $line_item->qty;
        //var_dump($order_item->subtotal);die;
        $order_item->tax_amount = $line_item->tax_amount;
        $order_item->row_total = $order_item->subtotal + $order_item->tax_amount;
        $order_item->price_incl_tax = $order_item->row_total;
        $order_item->created_at = time();
        $order_item->updated_at = time();
        if (!$order_item->save()) {
            \Yii::$app->getSession()->setFlash('error', "There was an error when Sales Order Item #" . $this->order->id);
            if (YII_ENV_DEV)
                VarDumper::dump($order_item->errors, 10, true);
            return null;
        }

        return $order_item;
    }

    private function createSalesOrderPayment($type, $charge_object)
    {
        $sales_order_payment = new SalesOrderPayment();
        $sales_order_payment->order_id = $this->order->id;
        $sales_order_payment->method = $type;


        if ($type == 'stripe') {
            $charge_value = number_format(($charge_object->amount / 100), 2, '.', '');
            $card = $charge_object->source;
            $sales_order_payment->amount_paid = $charge_value;
            $sales_order_payment->amount_authorized = $charge_value;
            $sales_order_payment->amount_ordered = $charge_value;
            $sales_order_payment->cc_owner = $card->name;
            $sales_order_payment->cc_exp_month = (string)$card->exp_month;
            $sales_order_payment->cc_exp_year = (string)$card->exp_year;
            $sales_order_payment->cc_last4 = $card->last4;
            $sales_order_payment->cc_type = $card->brand;
            $sales_order_payment->cc_approval = $charge_object->outcome->network_status;
            $sales_order_payment->cc_status_description = $charge_object->status;
            $sales_order_payment->stripe_token = $charge_object->id;
        } elseif ($type == 'paypal') {
            $charge_value = $charge_object->transactions[0]->amount->total;
            $card = $charge_object->payer->funding_instruments[0]->credit_card;
            $sales_order_payment->amount_paid = $charge_value;
            $sales_order_payment->amount_authorized = $charge_value;
            $sales_order_payment->amount_ordered = $charge_value;
            $sales_order_payment->cc_owner = "$card->first_name $card->last_name";
            $sales_order_payment->cc_exp_month = $card->expire_month;
            $sales_order_payment->cc_exp_year = $card->expire_year;
            $sales_order_payment->cc_last4 = str_replace('x', '', $card->number);
            $sales_order_payment->cc_type = $card->type;
            $sales_order_payment->cc_approval = $charge_object->state;
            $sales_order_payment->cc_status_description = $charge_object->state;
        } else {
            $charge_value = number_format(($charge_object->amount / 100), 2, '.', '');
            $card = $charge_object->source;
            $sales_order_payment->amount_paid = $charge_value;
            $sales_order_payment->amount_authorized = $charge_value;
            $sales_order_payment->amount_ordered = $charge_value;
            $sales_order_payment->cc_owner = $card->name;
            $sales_order_payment->cc_exp_month = (string)$card->exp_month;
            $sales_order_payment->cc_exp_year = (string)$card->exp_year;
            $sales_order_payment->cc_last4 = $card->last4;
            $sales_order_payment->cc_type = $card->brand;
            $sales_order_payment->cc_approval = $charge_object->outcome->network_status;
//            $sales_order_payment->cc_status_description = $charge_object->status;
//            $sales_order_payment->stripe_token = $charge_object->id;
        }

        if (!$sales_order_payment->save()) {
            \Yii::$app->getSession()->setFlash('error', "There was an error when creating Sales Payment");
            if (YII_ENV_DEV)
                VarDumper::dump($sales_order_payment->errors, 10, true);
        }
        return true;
    }
    /** =====
     * Create order functions END
     * ===== */


    /** =====
     * Funding functions START
     * ===== */
    public function processPayment()
    {

        $payment_settings = $this->paymentSettings;
        if ($payment_settings->stripe_enabled) {
            if ($this->stripe_token) {
                return $this->processStripePayment($payment_settings);
            }
            return false;
        } else if ($payment_settings->cardconnect_enabled) {
            if ($this->processCardConnectPayment($payment_settings)) {
                return true;
            }
            return false;
        }
        return true;
    }

    function authCardConnectTransaction($request)
    {

        echo "\nAuthorization Request\n";
        //$client = new CardConnectRestClient();
        var_dump(4);
        die;
        //$response = $client->authorizeTransaction($request);
        print var_dump($response);
        die;
        //return $response["retref"];
    }

    function detectCreditCardType($str, $format = 'string')
    {
        if (empty($str)) {
            return false;
        }

        $cardPatterns = [
            'visa' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
            'mastercard' => '/^5[1-5][0-9]{14}$/',
            'amex' => '/^3[47][0-9]{13}$/',
            'diners' => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',
            'discover' => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            'jcb' => '/^(?:2131|1800|35\d{3})\d{11}$/',
            'any' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/'
        ];

        $i = 1;
        foreach ($cardPatterns as $key => $pattern) {
            if (preg_match($pattern, $str)) {
                return $format == 'string' ? $key : $i;
            }
            $i++;
        }
    }

    private function processCardConnectPayment($payment_settings)
    {
        // try {

        echo "\nAuthorization Request\n";
        $client = new CardConnectRestClient(self::CARD_CONNECT_URL_PROD, self::CARD_CONNECT_USER_PROD, self::CARD_CONNECT_PASS_PROD);

        if (YII_ENV_DEV) {
            $client = new CardConnectRestClient(self::CARD_CONNECT_URL, self::CARD_CONNECT_USER, self::CARD_CONNECT_PASS);
        }


        $request = [
            'merchid' => (YII_ENV_DEV) ? self::CARD_CONNECT_MERCHANT : self::CARD_CONNECT_MERCHANT_PROD,
            'accttyppe' => "VISA",
            'account' => $this->card_number,
            'expiry' => $this->card_exp_month . $this->card_exp_year,
            'cvv2' => $this->card_cvc,
            'amount' => $this->order->grand_total,
            'currency' => "USD",
            'orderid' => $this->order->id,
            'name' => $this->card_name,
            'street' => $this->billing_street_address,
            'city' => $this->billing_city,
            'region' => $this->billing_subregion_id,
            'country' => "US",
            'postal' => $this->billing_zipcode,
            'tokenize' => "Y",
            'capture' => "Y",
        ];

        if ($response = $client->authorizeTransaction($request)) {
            return $response["retref"];
        }


        return false;
    }

    private function processStripePayment($payment_settings)
    {
        try {
            $stripe_key = '';

            if ($payment_settings->stripe_test_mode) {
                $stripe_key = $payment_settings->stripe_test_secret_key;
            } else {
                $stripe_key = $payment_settings->stripe_live_secret_key;
            }

            \Stripe\Stripe::setApiKey($stripe_key);

            $email = Yii::$app->user->identity ? Yii::$app->user->identity->email : $this->customer_email;
            $amount = (string)((int)($this->order->grand_total * 100));

            if ($amount <= 0) {
                Yii::$app->getSession()->setFlash('error', "Amount set to: $amount, grandtotal: " . $this->order->grand_total);
                return false;
            }

            $charge_object = \Stripe\Charge::create(array(
                "amount" => $amount,
                "currency" => "usd",
                "source" => $this->stripe_token,
                "description" => "SME charge for $email",
                "metadata" => [
                    'order_id' => $this->order->order_id
                ]
            ));

            return $this->createSalesOrderPayment('stripe', $charge_object);

        } catch (\Stripe\Error\Card $e) {                      //Card declined
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 8");
        } catch (\Stripe\Error\RateLimit $e) {               //Too many requests made to the API too quickly
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 9");
        } catch (\Stripe\Error\InvalidRequest $e) {          //Invalid parameters were supplied to Stripe's API
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 10");
        } catch (\Stripe\Error\Authentication $e) {          //Authentication with Stripe's API failed
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 11");
        } catch (\Stripe\Error\ApiConnection $e) {           //Network communication with Stripe failed
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 12");
        } catch (\Stripe\Error\Base $e) {                    // Display a very generic error to the user
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 13");
        } catch (\Exception $e) {                            // Something else happened, completely unrelated to Stripe
            Yii::$app->getSession()->setFlash('error', $e->getMessage() . " 14");
        }

        return false;
    }

    private function processPaypalPayment($payment_settings)
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $payment_settings->paypal_client_id,     // ClientID
                $payment_settings->paypal_client_secret  // ClientSecret
            )
        );

        $card = new \PayPal\Api\PaymentCard();

        $card->setType((string)$this->detectCreditCardType($this->paypal_card_number))
            ->setNumber((string)$this->paypal_card_number)
            ->setExpireMonth((string)$this->paypal_exp_month)
            ->setExpireYear((string)$this->paypal_exp_year)
            ->setCvv2((string)$this->paypal_cvc)
            ->setFirstName((string)$this->paypal_card_first_name)
            ->setLastName((string)$this->paypal_card_last_name)
            ->setBillingCountry("US");

        $fi = new \PayPal\Api\FundingInstrument();
        $fi->setPaymentCard($card);

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        $details = new \PayPal\Api\Details();
        $details->setShipping(Yii::$app->cart->getShipping())
            ->setTax(Yii::$app->cart->getSalesTax())
            ->setSubtotal(Yii::$app->cart->getCost());

        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency("USD")
            ->setTotal(Yii::$app->cart->getTotal())
            ->setDetails($details);

        $email = Yii::$app->user->identity ? Yii::$app->user->identity->email : $this->customer_email;
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount)
            ->setDescription("SME charge for $email")
            ->setInvoiceNumber(uniqid());

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));


        try {
            $payment->create($apiContext);

            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $authorization = $relatedResources[0]->getAuthorization();

            try {
                $payment = \PayPal\Api\Payment::get($relatedResources[0]->sale->parent_payment, $apiContext);

                return $this->createSalesOrderPayment('paypal', $payment);

            } catch (\Exception $e) {
                \Yii::$app->getSession()->setFlash('error', $e->getMessage());
                return false;
            }
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            $output = 'PayPal: ';
            $error_message = Json::decode($e->getData());
            if (array_key_exists('message', $error_message)) {
                $output .= $error_message['message'];
            }
            if (array_key_exists('error', $error_message)) {
                $output .= $error_message['error'];
            }
            \Yii::$app->getSession()->setFlash('error', $output);
            return false;
        } catch (\Exception $e) {
//            \Yii::$app->getSession()->setFlash('error', $e);
            return false;
        }
    }
    /** =====
     * Funding functions END
     * ===== */


    /** =====
     * Post-success START
     * ===== */
    public function closeCart()
    {
        //Yii::$app->cart->checkOut(false);
    }

    public function confirmationEmail()
    {
        $email_to = Yii::$app->user->identity ? Yii::$app->user->identity->email : $this->customer_email;
        $email_from = "orders@smeincusa.com";
        $email_subject = Yii::$app->name . " Order Confirmation";
        $email_content = "Order Confirmation message goes here";

//        MailHandler::send($email_to, $email_subject, $email_content, $email_from);
        return true;
    }

    /** =====
     * Post-success END
     * ===== */


    private function removeFailedData()
    {
        if ($this->order)
            $this->order->delete();
        if ($this->order_address_shipping)
            $this->order_address_shipping->delete();
        if ($this->order_address_billing)
            $this->order_address_billing->delete();
        if (count($this->order_items) > 0) {
            foreach ($this->order_items as $order_item) {
                $order_item->delete();
            }
        }
    }

    public function getSalesOrderIncrement()
    {
        $order = SalesOrder::findOne(self::getSalesOrder());
        return $order->order_id;
    }

    public function getSalesOrder()
    {
        return $this->order->id;
    }

    public function getSubTotal()
    {
        return (float)$this->order->subtotal;
    }

    public function saveCheckoutForm()
    {
        try {
            $success = true;

            /**
             * cascading success validator. it's gross, but it works
             */

            if ($success)
                $success = $this->validateUser();
            if ($success)
                $success = $this->createOrder();
            if ($success && $this->order->grand_total > 0)
                $success = $this->processPayment();
            if ($success) {   //return if order and payment are successful

                //clear all the Cart session data
                Yii::$app->session->remove('Cart');
                Yii::$app->session->remove('discount_code');
                //$this->confirmationEmail();
                return $success;
            }
        } catch (\Exception $e) {                              //catch generic PHP error
            \Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (Exception $e) {                               //catch Yii2 error
            \Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\Card $e) {                      //Card declined
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {               //Too many requests made to the API too quickly
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {          //Invalid parameters were supplied to Stripe's API
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\Authentication $e) {          //Authentication with Stripe's API failed
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {           //Network communication with Stripe failed
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        } catch (\Stripe\Error\Base $e) {                    // Display a very generic error to the user
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        }

        //something failed
        $this->removeFailedData();  //wipe out any in-progress data
        return false;
    }

    public function isOrderSupervised()
    {
        if (CoreConfig::getStoreConfig('general/supervisor/active') == 1) {
            if (CoreConfig::getStoreConfig('general/supervisor/threshold') > 0) {
                if ($this->order->grand_total <= CoreConfig::getStoreConfig('general/supervisor/threshold')) {
                    $this->order->status = SalesOrderStatus::ACCEPTED;
                    $this->order->save(false);
                    return false;
                } else {

                    $this->order->status = SalesOrderStatus::PENDING;
                    $this->order->save(false);
                    return true;
                }
            } else {

                $this->order->status = SalesOrderStatus::PENDING;
                $this->order->save(false);
                return true;
            }
        } else {

            $this->order->status = SalesOrderStatus::ACCEPTED;
            $this->order->save(false);
            return false;
        }
    }

    public function preFillForm()
    {
        if ($customer = Customer::findOne(['id' => CurrentUser::getUserId()])) {
            $this->shipping_first_name = $customer->first_name;
            $this->shipping_last_name = $customer->last_name;
            $this->billing_first_name = $customer->first_name;
            $this->billing_last_name = $customer->last_name;
        }

        if ($customerShipping = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'default_shipping' => 1])) {
            $this->shipping_first_name = $customerShipping->firstname;
            $this->shipping_last_name = $customerShipping->lastname;
            $this->shipping_street_address = $customerShipping->address_1;
            $this->shipping_apartment_suite = $customerShipping->address_2;
            $this->shipping_city = $customerShipping->city;
            //$this->shipping_region_id = $customerShipping->region_id;
            $this->shipping_subregion_id = $customerShipping->region_id;
            $this->shipping_zipcode = $customerShipping->postcode;
            $this->shipping_phone = $customerShipping->phone;
        }

        if ($customerBilling = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'default_billing' => 1])) {
            $this->billing_first_name = $customerBilling->firstname;
            $this->billing_last_name = $customerBilling->lastname;
            $this->billing_street_address = $customerBilling->address_1;
            $this->billing_apartment_suite = $customerBilling->address_2;
            $this->billing_city = $customerBilling->city;
            //$this->billing_region_id = $customerBilling->region_id;
            $this->billing_subregion_id = $customerBilling->region_id;
            $this->billing_zipcode = $customerBilling->postcode;
            $this->billing_phone = $customerBilling->phone;
        }

        if (isset($customerBilling) && isset($customerShipping)) {
            if ($customerBilling !== $customerShipping) {
                $this->billing_is_shipping = false;
            } else {
                $this->billing_is_shipping = true;
            }
        }
    }
}
