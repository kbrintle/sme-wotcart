<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\components\CurrentStore;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="account">
    <div class="container">
        <div class="site-request-password-reset content-pad">
            <div class="row">
                <?= isset($prompt) ? "<div class='text-center'><h2>$prompt</h2></div>" : "";?>
                <div class="col-lg-6 col-md-6  col-lg-offest-3 col-md-offset-3 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>Please fill out your email. A link to reset password will be sent there.</h3>
                        </div>
                        <div class="panel-body">
                            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'action' => \app\components\StoreUrl::to('/account/request-password-reset')]); ?>

                            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Send', ['class' => 'btn btn-primary btn-responsive center-block']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

