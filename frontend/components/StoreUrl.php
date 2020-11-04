<?php

namespace app\components;

use yii;
use common\components\CurrentStore;
use common\models\core\Store;

class StoreUrl
{

    public static function to($url = '', $store = 0)
    {

        if($store){
            $store = Store::findOne(['id' => $store])->url;
        }else{
            if(CurrentStore::getStoreId()){
                $store = CurrentStore::getStore()->url;
            }else{
                $store = CurrentStore::getDefaultStore()->url;
            }
        }


        $url = Yii::getAlias($url);

        if ($url === '') {
            $url = Yii::$app->getRequest()->getUrl();
        }

        $url = ltrim($url, '/');

        return ($store ? "/$store/$url" : $url);
    }

    public static function homeUrl()
    {
        $store = CurrentStore::getStore()->url;

        return ($store ? "/$store" : '');
    }

    public static function canonical($url = '', $store = 0)
    {
        $store = Store::findOne(['id' => $store])->url;
        $url = Yii::getAlias($url);

        if ($url === '') {
            $url = Yii::$app->getRequest()->getUrl();
        }

        return ($store ? Yii::$app->getHomeUrl() . "$store/$url" : Yii::$app->getHomeUrl() . $url);
    }

    public static function themeImage($url = '')
    {
        $url = Yii::getAlias($url);

        return ($url ? "/themes/default/_assets/src/images/" . $url : '');
    }

    public static function uploadedImage($url = '')
    {
        $url = Yii::getAlias($url);

        if ($url === '') {
            $url = Yii::$app->getRequest()->getUrl();
        }

        return ($url ? "/uploads/" : $url);
    }

}