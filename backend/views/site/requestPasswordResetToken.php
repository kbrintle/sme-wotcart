<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="row">
            <div class="logo">
                <a href="<?php echo Yii::$app->homeUrl ?>"><img class="center-block" alt="<?php Yii::$app->name ?>"
                                                                src="<?php echo Yii::$app->homeUrl ?>/_assets/src/images/SME-logo-new.png"/></a>
            </div>
        </div>
    </div>
</div>
    <?= isset($prompt) ? "<div class='text-center'><h3>$prompt</h3></div>" : ""; ?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="row">
            <div class="site-login">
                <div class="panel panel__ui">
                    <div class="panel-heading">
                        <h4 class=""><?= Html::encode($this->title) ?></h4>
                    </div>
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'action' => "/admin/site/request-password-reset"]); ?>

                        <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary center-block']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

