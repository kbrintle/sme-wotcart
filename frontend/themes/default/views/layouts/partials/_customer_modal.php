<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\StoreUrl;

?>
<!--Guest Modals-->

<div class="modal modal__ui modal__ui-customer fade" id="guest-log-in" tabindex="-1" role="dialog" aria-labelledby="guestloginModal" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div id="modal-guest">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Enter your email below to checkout as a Guest.</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php $form = ActiveForm::begin([
                                'id' => 'guest-form',
                                'action' => StoreUrl::to('account/guest')
                            ]); ?>
                                <div class="form-group">
                                    <?= $form->field($guest_form, 'email')->textInput(['class' => 'form-control']); ?>
                                </div>
                            <?= Html::submitButton('Proceed to Checkout', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'guest-button']) ?>
                            <?php ActiveForm::end(); ?>
                            <div class="pad-top">
                                <p class="account-prompt">Returning Customer? <button class="btn-switch" id="login-link">Log in</button></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-login" style="display: none;">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Log in to complete your purchase.</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php $form = ActiveForm::begin([
                                'id' => 'login-form',
                                'action' => StoreUrl::to('account/login?checkout=true')

                            ]); ?>
                                <?= $form->field($login_form, 'email')->textInput(['autofocus' => true]) ?>
                                <?= $form->field($login_form, 'password')->passwordInput() ?>
                                <div class="row pad-btm-sm">
                                    <div class="col-md-6 col-xs-6">
                                        <?= $form->field($login_form, 'rememberMe')->checkbox() ?>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <?php echo Html::a('Forgot Password?', StoreUrl::to('account/request-password-reset'), ['class' => 'pull-right sm-a']); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?= Html::submitButton('Proceed to Checkout', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'login-button']) ?>
                                </div>
                                <div class="form-group text-center">
                                    <p class="md">Don't have an account? <a href="<?php echo StoreUrl::to("account/register");?>">Sign Up</a></p>
                                </div>
                            <?php ActiveForm::end(); ?>
                            <div class="pad-top">
                                <p class="account-prompt">Checkout as <button class="btn-switch" id="guest-link">Guest</button></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


