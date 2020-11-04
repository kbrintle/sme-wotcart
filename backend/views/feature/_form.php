<?php

use yii\helpers\Html;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogFeature */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-product-feature-form">

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?php echo $form->field($model, 'description')->textarea(['rows' => 6]); ?>

    <?php echo $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>

</div>
