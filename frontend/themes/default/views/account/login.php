<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\StoreUrl;

$this->title = 'Login';

?>
<section class="account">
    <div class="container">
        <div class="site-login content-pad">
            <div class="row">
        <!--        <div class="col-lg-6 col-md-6 col-lg-offest-3 col-md-offset-3 col-xs-12">
                    <div class="alert alert-danger alert-dismissible show text-center margin-bottom-1x">
                        <span class="alert-close" data-dismiss="alert"></span>
                        <i class="icon-ban"></i>
                        <strong>Notice:</strong><br/>
                        All first time users of the new site are required to update their password in order to maintain a high level of site security.<br/><br/>
                        <h4>NOTE: If you have already reset your password or received one from our web team, you may bypass this step.</h4>
                        <a href="<?/*=StoreUrl::to('account/request-password-reset') */?>" style="display: inline-block; margin-top:10px;" class="margin-top-20 btn btn-default"> Reset Password</a>
                    </div>
                </div>-->
                <div class="col-lg-4 col-md-4 col-lg-offest-4 col-md-offset-4 col-xs-12">

                    <div class="panel">
                        <div class="panel-heading">
                            <h3>Log In As An Existing Customer</h3>
                        </div>
                        <div class="panel-body">
                            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                                <?= $form->field($model, 'password')->passwordInput() ?>

                                <div class="row pad-btm-sm">
                                    <div class="col-md-6 col-xs-6">
                                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <?php echo Html::a('Forgot Password?', StoreUrl::to('account/request-password-reset'), ['class' => 'pull-right sm-a']); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg btn-responsive center-block', 'name' => 'login-button']) ?>
                                </div>

                                <div class="form-group text-center">
                                    <p class="md">Don't have an account? <a href="<?php echo StoreUrl::to("account/register");?>">Sign Up</a></p>
                                </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
