<?php

use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreFlyer */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="store-flyer-form">
    <small class="pad" >jpg recommended</small>
    <p></p>
    <?= $form->field($upload, 'file')->fileInput()->label('Select File') ?>
    <?= $form->field($model, 'category')->dropdownList([],['value'=>'yes'])->label("Category"); ?>
    <?= $form->field($model, 'title')->textInput()->label("Banner Title") ?>
    <?= $form->field($model, 'content')->textarea(['id'=>'textInput', 'rows' => 6, 'maxlength'=>'225'])->label("Text - <small>225 Max Characters</small>") ?>
    <?= $form->field($model, 'button_text')->textInput()->label("Button Text") ?>
    <?= $form->field($model, 'url')->textInput()->label("Button Url") ?>
    <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['value'=>'yes'])->label("Active"); ?>

</div>
