<?php

namespace backend\components;

use Yii;
use common\models\core\Admin;

class CurrentUser
{
    
    public static function getRoleId($user_id = 0) {
        $user_id = ($user_id ? $user_id : Yii::$app->user->id);
        return (Admin::findOne($user_id) ? Admin::findOne($user_id)->role_id : null);
    }

    public static function isOperations($user_id = 0) {
        $user_id = ($user_id ? $user_id : Yii::$app->user->id);
        return self::getRoleId($user_id) == Admin::ROLE_OPS;
    }

    public static function isAdmin($user_id = 0) {
        $user_id = ($user_id ? $user_id : Yii::$app->user->id);
        return self::getRoleId($user_id) == Admin::ROLE_ADMIN;
    }

    public static function isStoreAdmin($user_id = 0) {
        $user_id = ($user_id ? $user_id : Yii::$app->user->id);
        return self::getRoleId($user_id) == Admin::ROLE_STORE;
    }

    public static function getUserId(){
        return Yii::$app->user->id;
    }
    
}