<?php

/* @var $this yii\web\View */
/* @var $model common\models\Store */
/* @var $form yii\widgets\ActiveForm */
use yii\helpers\ArrayHelper;
use common\components\helpers\FormHelper;
use common\models\core\StoreGroup;
?>

<div class="store-form">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'group_id')->dropdownList(ArrayHelper::map(StoreGroup::find()->all(), 'id', 'name')) ?>

    <?= (!$model->isNewRecord) ? $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']) : ''; ?>
</div>