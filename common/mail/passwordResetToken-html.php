<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\customer/Customer */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl([\app\components\StoreUrl::to('account/reset-password'), 'token' => $user->access_token]);
?>
<div class="password-reset">
    <H2>Hello <?= Html::encode($user->first_name) ?>, Here's how to reset your password.</H2>

    <p>We received a request to reset your password on <a href="www.smeincusa.com">smeincusa.com</a>. If you did not make this request, please ignore this email and your password will not change.</p>

    <p>To reset your password, please click the button below.</p>

    <p style="padding: 30px 0; text-align: center;"><?= Html::a('Reset Password', $resetLink, ['style' => 'text-decoration: none; background: #2196F3; border-radius: 4px; padding: 12px 30px; font-size: 18px; color: #FFFFFF;']); ?></p>

    <hr style="border-width: 0; width: 100%; height: 2px; border-top: none; color: #ECEFF1; background: #ECEFF1;"/>

    <h3>Having Trouble?</h3>

    <p>Please let us know if you have any problems or questions by replying to this email or sending an email to <a href="mailto:kbrintle@wideopentech.com"> support@americasmattress.com</a>.</p>
</div>
