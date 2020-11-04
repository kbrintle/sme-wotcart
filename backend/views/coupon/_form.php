<?php

use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreCoupon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-flyer-form">
    <small class="pad color-AmMatRed" >* 264px x 340 png, jpg recommended</small>
    <p></p>
    <?= $form->field($upload, 'file')->fileInput()->label('Select File') ?>

    <?php echo $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>

</div>
