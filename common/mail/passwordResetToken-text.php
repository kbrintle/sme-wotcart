<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([\app\components\StoreUrl::to('account/reset-password'), 'token' => $user->access_token]);
?>
Hello <?= $user->first_name ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
