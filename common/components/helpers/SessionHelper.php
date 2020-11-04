<?php

namespace common\components\helpers;

use Yii;

class SessionHelper
{

    public static function isFrontend() {
        $sessionName = Yii::$app->session->name;
        return $sessionName == 'advanced-frontend' ? true : false;
    }

    public static function isBackend() {
        $sessionName = Yii::$app->session->name;
        return $sessionName == 'advanced-backend' ? true : false;
    }

}