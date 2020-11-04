<?php

namespace frontend\components;

use common\models\customer\CustomerAddress;
use Yii;
use common\models\customer\Customer;

class CurrentCustomer
{
    public static function getCustomer(){
        return Customer::find()->where(['id'=>Yii::$app->user->id])->one();
    }
    public static function getCustomerId(){
        return Yii::$app->user->id;
    }
    public static function getCustomerShippingAddress(){
        return CustomerAddress::find()->where(['customer_id'=>Yii::$app->user->id, 'default_shipping'=>1])->one();
    }
    public static function getCustomerBillingAddress(){
        return CustomerAddress::find()->where(['customer_id'=>Yii::$app->user->id, 'default_billing'=>1])->one();
    }
    
}