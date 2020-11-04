<?php
namespace frontend\components;

use Yii;

class UtilitiesHelper {

    public static function isHome($controller){
        $default = Yii::$app->defaultRoute;

        if( $controller->id === $default ){
            if( $controller->action->id === $controller->defaultAction ){
                return true;
            }
        }

        return false;
    }

}