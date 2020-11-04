<?php

use yii\helpers\Html;
use common\components\helpers\FormHelper;
use nkovacs\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\promotion\PromotionCode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promotion-code-form">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'type')->dropdownList(FormHelper::getPromocodeOptions(), ['prompt' => 'Select one']); ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'event')->dropdownList(FormHelper::getEventOptions(), ['prompt' => 'Select one']); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'starts_at')->input('date') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ends_at')->input('date') ?>
            </div>
        </div>
    </div>
</div>
