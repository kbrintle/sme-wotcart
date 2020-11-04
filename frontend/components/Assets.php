<?php

namespace frontend\components;

use Yii;
use yii\helpers\Url;

class Assets
{

    public static function themeResource($url = '', $type = 'images')
    {
        $url = ltrim(Yii::getAlias($url), '/');
        $assets = Url::to("@assets");

        $cdn = (!YII_ENV_DEV) ? (Yii::$app->params['cdn']) ? Yii::$app->params['cdn'] : false : false;
        $base = $cdn ? $cdn : rtrim(str_replace('/admin', '', Url::home()), '/');

        return $url ? "$base/$assets/$type/$url" : '';
    }

    public static function productResource($url, $type = null)
    {
        $url = ltrim(Yii::getAlias($url), '/');
        $web_root = Url::to('@frontend')."/web";
        $place_holder = '/uploads/products/sme-placeholder.png';

        if (isset($type)) {
            $type = $type . '/';
        }

        $check = $web_root . '/uploads/products/' . $type . $url;

        if (file_exists($check)) {
            $image = '/uploads/products/' . $type . $url;;
        } else {
            $image = $place_holder;
        }

        return $url ? $image : $place_holder;
    }

    // Todo: refactor this into a switch in productResource, make it generic (getMedia, or something...)

    public static function mediaResource($url)
    {
        $url = ltrim(Yii::getAlias($url), '/');

        $cdn = (!YII_ENV_DEV) ? (Yii::$app->params['cdn']) ? Yii::$app->params['cdn'] : false : false;
        $base = $cdn ? $cdn : rtrim(Url::home(), '/');

        return $url ? "/uploads/$url" : '';
    }

    public static function promoResource($url)
    {
        $url = ltrim(Yii::getAlias($url), '/');

        $cdn = (!YII_ENV_DEV) ? (Yii::$app->params['cdn']) ? Yii::$app->params['cdn'] : false : false;
        $base = $cdn ? $cdn : rtrim(Url::home(), '/');

        return $url ? "$base/$url" : '';
    }

}