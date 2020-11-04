<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="row">
            <div class="logo">
                <a href="<?php echo Yii::$app->homeUrl ?>"><img class="center-block" alt="<?php Yii::$app->name ?>" src="<?php echo Yii::$app->homeUrl ?>/_assets/src/images/SME-logo-new.png" /></a>
            </div>
        </div>
        <div class="row">
            <div class="site-login">
                <div class="panel panel__ui">
                    <div class="panel-heading">
                        <h4 class=""><?= Html::encode($this->title) ?></h4>
                    </div>
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label('Email') ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

                        <div class="form-group text-center">
                            <?= Html::submitButton('Log In', ['class' => 'btn btn-primary btn-responsive btn-lg', 'name' => 'login-button']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                        <div class="form-group text-center">
                            <a href="request-password-reset">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

