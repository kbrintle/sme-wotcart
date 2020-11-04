<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsSeo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-seo-form">

    <?php $form = ActiveForm::begin([
        'id'=>'SettingsSeo'
    ]); ?>

    <?= $form->field($model, 'ga_code')->textInput(['maxlength' => true])->label('Google Analytics Account ID &nbsp;<small>Example: UA-xxxxxxxxx</small>') ?>

    <?= $form->field($model, 'page_title_prefix')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'page_title_suffix')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
    <?php ActiveForm::end(); ?>

</div>