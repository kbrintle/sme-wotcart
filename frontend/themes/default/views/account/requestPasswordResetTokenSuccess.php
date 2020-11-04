<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Request Password Reset';
?>
<section class="account">
    <div class="container">
        <div class="site-request-password-reset content-pad">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-lg-offest-3 col-md-offset-3 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading text-center">
                            <h3 class="pad-btm-sm">Check your email for further instructions.</h3>
<!--                            <a href="--><?//=\app\components\StoreUrl::to('account/login') ?><!--" class="btn btn-primary btn-responsive"> Login </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

