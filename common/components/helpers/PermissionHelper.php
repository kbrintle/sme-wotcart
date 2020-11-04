<?php

namespace common\components\helpers;

use Yii;
use common\components\CurrentStore;
use backend\components\CurrentUser;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PermissionHelper
{

    public static function notFound($error_message) {
        throw new NotFoundHttpException($error_message);
    }

    public static function byUserLevel($user_check, $error_message) {
        // $user_check is one of isAdmin or isStoreAdmin
        if (!$user_check)
            throw new ForbiddenHttpException($error_message);
    }

    public static function byStore($object, $error_message) {
        if (CurrentUser::isAdmin())
            return true;

        if (isset($object->store_id)) {
            if (CurrentStore::getStoreId() == $object->store_id)
                return true;
        }

        // Else, throw a 403 with $error_message
        throw new ForbiddenHttpException($error_message);
    }

    public static function byColumn($column, $value, $error_message) {
        if (CurrentUser::isAdmin())
            return true;

        if (isset($column)) {
            if ($column == $value)
                return true;
        }

        // Else, throw a 403 with $error_message
        throw new ForbiddenHttpException($error_message);
    }

}