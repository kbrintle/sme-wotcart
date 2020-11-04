<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\core\CoreUrlRewrite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="core-url-rewrite-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'id_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'request_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'target_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_system')->textInput() ?>

    <?= $form->field($model, 'options')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>


    <?php ActiveForm::end(); ?>
</div>