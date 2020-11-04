<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="account">
    <div class="container">
        <div class="site-reset-password content-pad">
            <div class="row">
                <div class="col-lg-6 col-md-6  col-lg-offest-3 col-md-offset-3 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3>Please choose your new password:</h3>
                        </div>
                        <div class="panel-body">
                            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                                <?= $form->field($model, 'password_repeat')->passwordInput(['autofocus' => true]) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-responsive center-block']) ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

