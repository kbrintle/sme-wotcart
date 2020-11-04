<?php

namespace frontend\controllers;

use app\components\StoreUrl;
use backend\components\CurrentUser;
use common\models\sales\SalesOrderAddress;
use Yii;
use common\models\settings\SettingsStore;
use common\models\sales\SalesOrder;
use frontend\models\CheckoutForm;
use frontend\models\GuestForm;
use frontend\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use common\components\Notify;
use common\models\catalog\CatalogProduct;
use common\models\sales\SalesQuote;
use common\models\customer\CustomerReward;
use yii\helpers\VarDumper;
use common\models\customer\CustomerAddress;
use common\models\core\CountryRegion;
use yii\filters\AccessControl;


class CheckoutController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delivery-method', 'process', 'confirm', 'order', 'fill-shipping-billing-ajax'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'delivery-method', 'process', 'confirm', 'order', 'fill-shipping-billing-ajax'],
                        'roles' => ['@'],
                    ],
                ], 'denyCallback' => function ($rule, $action) {
                    return $this->redirect([StoreUrl::to('account/login')]);
                }
            ],
        ];
    }

    public function actionIndex()
    {
        /**
         * Models For Modals (that sounds like Toys For Tots. that's adorable)
         */
        $model = new CheckoutForm();
        $login_form = new LoginForm();
        $guest_form = new GuestForm();

        //ajax validation
        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        } else { //customer data pre-fill
            $model->preFillForm();
        }

        $this->view->title = "Checkout";
        $model->setCustomer($guest_form->session);

        if ($post = Yii::$app->request->post()) {

            if ($model->load($post)) {
                $model->setBillingAsShipping();
                if ($model->validate()) {
                    if ($model->saveCheckoutForm()) {
                        CustomerReward::subtractPoints($model);
                        CustomerReward::addPoints($model);
                        $supervised_order = $model->isOrderSupervised();

                        if (SalesOrder::checkOrder($model->getSalesOrder())) {
                            $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'admin'));
                            $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'store'));
                            $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'user'));
                            $this->confirmationEmail($model->getSalesOrder(), SalesOrder::getConfirmationRecipients($model->getSalesOrder(), 'supervisor', $supervised_order));
                            SalesQuote::convertToOrder();
                            return $this->redirect(StoreUrl::to('/checkout/confirm?invoice=' . $model->getSalesOrderIncrement()));
                        } else {
                            Yii::$app->getSession()->setFlash('error', 'There was an error while processing your checkout, please try again');
                            return $this->redirect(StoreUrl::to('/checkout'));
                        }
                    }
                }
            }
        }
        $quote = SalesQuote::getItems();
        if (array_key_exists("items", $quote)) {
            if (count($quote["items"]) > 0) {
                $this->layout = 'checkout';
                return $this->render('index', [
                    'model' => $model,
                    'login_form' => $login_form,
                    'guest_form' => $guest_form,
                    'quote' => $quote
                ]);
            }
        }

        return $this->redirect(StoreUrl::to('cart/index'));

    }


    public function actionDeliveryMethod()
    {
        $request = Yii::$app->request;

        if ($request->isPost) {
            $method = $request->post('method');

            if ($method) {
                Yii::$app->cart->setShippingMethod($method);
            }
        }

        return $this->renderPartial('../checkout/partials/_order_summary', [
            'quote' => SalesQuote::getItems()
        ]);
    }


    public function actionConfirm()
    {
        $sales_order_id = Yii::$app->request->get('invoice');

        if (!$sales_order_id) {
            return $this->redirect(StoreUrl::to('/account/overview'));
        }
        $sales_order = SalesOrder::find()->where(['order_id' => $sales_order_id])->one();
        //$this->confirmationEmail($sales_order_id);
        $this->layout = 'main';
        return $this->render('confirm', [
            'sales_order' => $sales_order
        ]);
    }

    public function actionOrder()
    {
        $this->layout = 'checkout';
        return $this->render('order');
    }

    public function confirmationEmail($id = 0, $recipents)
    {
        $order = SalesOrder::findOne($id);

        $itemArray = [];
        foreach ($order->items as $item) {

            $name = CatalogProduct::getName($item->product_id);
            $options = CatalogProduct::getProductCustomOptions($item->product_id, $item->sku);
            foreach ($options as $option) {
                $name .= " - $option";
            }

            $itemArray[] =
                [
                    'name' => $name,
                    'sku' => $item->sku,
                    'qty' => $item->qty_ordered,
                    'item_price' => $item->price,
                    'item_subtotal' => $item->subtotal
                ];
        }

        $billing = SalesOrderAddress::find()->where(['id' => $order->billing_address_id])->one();
        $shipping = SalesOrderAddress::find()->where(['id' => $order->shipping_address_id])->one();

        if (isset($billing)) {

            $billing_address =
                [
                    "firstname" => $billing->firstname,
                    "lastname" => $billing->lastname,
                    "address" => $billing->street,
                    "address2" => $billing->street2,
                    "city" => $billing->city,
                    "state" => CountryRegion::getRegionById($billing->region_id)->code,
                    "zip" => $billing->postcode,
                    "phone" => $billing->telephone,
                ];
        }
        if (isset($shipping)) {
            $shipping_address = [
                "firstname" => $shipping->firstname,
                "lastname" => $shipping->lastname,
                "address" => $shipping->street,
                "address2" => $shipping->street2,
                "city" => $shipping->city,
                "state" => CountryRegion::getRegionById($shipping->region_id)->code,
                "zip" => $shipping->postcode,
                "phone" => $shipping->telephone,
            ];
        }
        if ($order) {


            if (isset($recipents) && !empty($recipents[0])) {
                $data = [
                    "personalizations" => [[
                        'to' => $recipents,
                        'dynamic_template_data' => [
                            'logo' => SettingsStore::getLogo(true),
                            "order_id" => $order->order_id,
                            "total" => $order->grand_total,
                            "subtotal" => $order->subtotal,
                            "shipping" => $order->shipping_amount,
                            "discount" => ($order->discount_amount > 0) ? $order->discount_amount : null,
                            "reward_discount" => ($order->discount_reward_amount > 0) ? $order->discount_reward_amount : null,
                            "tax" => $order->tax_amount,
                            "customer_firstname" => $order->customer_firstname,
                            "customer_lastname" => $order->customer_lastname,
                            "customer_email" => $order->customer_email,
                            "items" => $itemArray,
                            "billing" => $billing_address,
                            "shipping" => $shipping_address,
                            "purchase_order" => $order->purchase_order,

                            "notes" => $order->customer_note
                        ],
                    ]],
                    "from" => [
                        "email" => Yii::$app->params['from_email']['address'],
                        "name" => Yii::$app->params['from_email']['name']
                    ],

                    "template_id" => "d-7882ac0743ae431b848ed3e13e3d1d5e"
                ];
                //print_r($data);
                if (Notify::sendJsonMail($data)) {
                    return true;
                }
            }
        }
        return true;
    }

//    public function confirmationCustomerEmail($id = 0, $supervised_order = false, $sales_email = false)
//    {
//        $order = SalesOrder::findOne($id);
//
//        $itemArray = [];
//        foreach ($order->items as $item) {
////            $base_image = CatalogProduct::getGalleryImages($item->product_id, 'base-image');
////            $image = $base_image ? Assets::productResource($base_image->value) : Assets::mediaResource('');
//
//            $name = CatalogProduct::getName($item->product_id);
//            $options = CatalogProduct::getProductCustomOptions($item->product_id, $item->sku);
//
//            foreach ($options as $option) {
//                $name .= " - $option";
//            }
//
//            $itemArray[] =
//                [
//                    'name' => $item->name,
//                    //'image'         => Yii::$app->params['base_url'].$image,
//                    'sku' => $item->sku,
//                    'qty' => $item->qty_ordered,
//                    'item_price' => $item->price,
//                    'item_subtotal' => $item->subtotal
//                ];
//        }
//
//        $billing = SalesOrderAddress::find()->where(['id' => $order->billing_address_id])->one();
//        $shipping = SalesOrderAddress::find()->where(['id' => $order->shipping_address_id])->one();
//
//        if (isset($billing)) {
//
//            $billing_address =
//                [
//                    "firstname" => $billing->firstname,
//                    "lastname" => $billing->lastname,
//                    "address" => $billing->street,
//                    "city" => $billing->city,
//                    "state" => CountryRegion::getRegionById($billing->region_id)->code,
//                    "zip" => $billing->postcode,
//                    "phone" => $billing->telephone,
//                ];
//        }
//        if (isset($shipping)) {
//            $shipping_address = [
//                "firstname" => $shipping->firstname,
//                "lastname" => $shipping->lastname,
//                "address" => $shipping->street,
//                "city" => $shipping->city,
//                "state" => CountryRegion::getRegionById($shipping->region_id)->code,
//                "zip" => $shipping->postcode,
//                "phone" => $shipping->telephone,
//            ];
//        }
//        if ($order) {
//
//            if (YII_ENV_DEV) {
//                $to[] = ["email" => 'kbrintle+test@wideopentech.com'];
//            } else {
//                $to[] = [
//                    "email" => $order->customer_email,
//                    "name" => "$order->customer_firstname $order->customer_lastname"
//                ];
//            }
//
//
//            if (isset($to) && !empty($to[0])) {
//                $data = [
//                    "personalizations" => [[
//                        'to' => $to,
//
//                        'dynamic_template_data' => [
//                            'logo' => SettingsStore::getLogo(true),
//                            "order_id" => $order->order_id,
//                            "total" => $order->grand_total,
//                            "subtotal" => $order->subtotal,
//                            "shipping" => $order->shipping_amount,
//                            "discount" => ($order->discount_amount) ? $order->discount_amount : null,
//                            "reward_discount" => ($order->discount_reward_amount) ? $order->discount_reward_amount : null,
//                            "tax" => $order->tax_amount,
//                            "customer_firstname" => $order->customer_firstname,
//                            "customer_lastname" => $order->customer_lastname,
//                            "customer_email" => $order->customer_email,
//                            "items" => $itemArray,
//                            "billing" => $billing_address,
//                            "shipping" => $shipping_address,
//                            "payment" => [
//                                "purchase_order" => $order->purchase_order,
//                                "method" => '',
//
//                            ],
//                            "notes" => $order->customer_note
//                        ],
//                        //'subject' => "Order Confirmation - Order #  ". $order->order_id. "",  //Subject is configured via SendGrid
//                    ]],
//                    "from" => [
//                        "email" => Yii::$app->params['from_email']['address'],
//                        "name" => Yii::$app->params['from_email']['name']
//                    ],
//
//                    "template_id" => "d-7882ac0743ae431b848ed3e13e3d1d5e"
//                ];
//
//                if (Notify::sendJsonMail($data)) {
//                    return true;
//                }
//            }
//        }
//        return true;
//    }

    public function actionFillShippingBillingAjax()
    {
        if (Yii::$app->request->isAjax) {
            if ($request = Yii::$app->request->post()) {
                return json_encode(CustomerAddress::find()
                    ->select(['firstname', 'lastname', 'address_1', 'address_2', 'city', 'region_id', 'postcode', 'phone'])
                    ->where(["address_id" => $request["addressId"], "customer_id" => CurrentUser::getUserId()])
                    ->asArray()
                    ->one());
            }
        }
    }

}