<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account pad-xs">
    <div class="account-information">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="sidebar">
                        <?php echo $this->render('_nav.php') ?>
                    </div>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel__ui">
                                <div class="panel-heading panel__ui-heading">
                                    <h3 class="panel__ui-heading-ttl">Account Information</h3>
                                </div>
                                <div class="panel-body panel__ui-body">
                                    <?php $form = ActiveForm::begin(['id' => 'password_reset-form']); ?>


                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <?= $form->field($model, 'password_confirm')->passwordInput(['class'=>'form-control']); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6">
                                            <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control']); ?>
                                        </div>
                                        <div class="col-xs-12 col-md-6">
                                            <?= $form->field($model, 'password_repeat')->passwordInput( ['class'=>'form-control']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-responsive']); ?>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
