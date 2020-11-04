<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="location-form">

    <div class="row">
        <div class="col-xs-12 col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'type')->dropDownList([
                'store'     => 'Store',
                'gallery'   => 'Gallery'
            ],
                [
                    'prompt' => 'Select a Type'
                ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-12">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'alt_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'state')->dropDownList(
                $model->us_state_abbreviations,
                [
                    'prompt' => 'Select a State'
                ]) ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'country')->dropDownList(
                [
                    'US' => 'United States',
                    'CA' => 'Canada'
                ],
                [
                    'prompt' => 'Select a Country'
                ]) ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'zipcode')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <h3>Map</h3>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'longtitude')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12 col-md-4">
            <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

</div>