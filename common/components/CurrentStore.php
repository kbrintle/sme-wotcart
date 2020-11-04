<?php

namespace common\components;

use Yii;
use common\models\settings\SettingsPayment;
use common\models\settings\SettingsSeo;
use common\models\settings\SettingsStore;
use common\models\store\StoreLocation;
use common\models\core\Store;
use common\components\helpers\SessionHelper;
use yii\helpers\VarDumper;
use yii\web\Cookie;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;
use common\models\sales\SalesQuote;

class CurrentStore{

    const ALL = 0;

    public static function setStore($store_id = 1){
        Yii::$app->session->set('current_store', $store_id);

        if (SessionHelper::isFrontend()) {
            SalesQuote::recalculateCartTotals();
        }
    }

    public static function getStore() {
        return Store::findOne(['id' => self::getStoreId()]);
    }

    public static function getDefaultStore() {
        return Store::findOne(['is_default' => true]);
    }

    public static function getStoreId() {
        $session = Yii::$app->session['current_store'];

        if (SessionHelper::isBackend())
            return "$session";

        $cookie  = Yii::$app->response->cookies->getValue('current_store', false);
        return $cookie ? "$cookie" : $session ? "$session" : false;
    }

    public function isDefault() {
        return $this->is_default;
    }

    public static function isNone() {
        return (self::getStoreId() == Store::NO_STORE ? true : false);
    }

    public static function isNational() {
        return (self::getStoreId() == Store::findOne(['is_default' => true])->id ? true : false);
    }



    public static function getSettings(){
        $store_id = self::getStoreId();

        $output = [
            'general'   => SettingsStore::findOne(['store_id'=>$store_id]),
            'seo'       => SettingsSeo::findOne(['store_id'=>$store_id]),
            'payment'   => SettingsPayment::findOne(['store_id'=>$store_id]),
            'shipping'  => null
        ];

        return $output;
    }

    /**
     * Get Location for CurrentStore. Used for Tax District detection in Avalara
     *
     * @return StoreLocation
     */
    public static function getStoreLocation(){
        $store_id = CurrentStore::getStoreId(); //preset the store_id

        if( $store_id
            && $store_id != 1 ){    //check if store_id exists and does not equal 1 (National)
            return StoreLocation::find()->where([   //return the StoreLocation that matches the store_id
                'store_id' => $store_id
            ])->one();
        }

        $location = new StoreLocation();    //make a spoof StoreLocation for National
        $location->address  = "35 E Golf Rd";
        $location->city     = "Schaumburg";
        $location->state    = "IL";
        $location->zipcode  = 60195;

        return $location;   //return the spoofed StoreLocation
    }
}