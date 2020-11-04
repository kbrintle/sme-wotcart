<?php

use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreCommercial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-commercial-form">

    <?= $form->field($model, 'url')->textarea() ?>

    <?php echo $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>

</div>
