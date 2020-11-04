<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreFinancing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-financing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_id')->hiddenInput(['value'=> \common\components\CurrentStore::getStoreId()])->label(false) ?>

    <?= $form->field($model, 'twenty_four_month_min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thirty_six_month_min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'forty_eight_month_min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sixty_month_min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'disclaimer_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>

    <?php ActiveForm::end(); ?>

</div>