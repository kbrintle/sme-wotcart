<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\customer\CustomerAddress;
use common\models\core\CountryRegion;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\customer\Lead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lead-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'practitioner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_position')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ordering_contact_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_state')->dropdownlist(ArrayHelper::map(CountryRegion::find()->where(['country_id'=>'US'])->all(), 'id', 'code')); ?>

    <?= $form->field($model, 'clinic_zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'clinic_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'network_member_list')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'how_hear')->textInput(['maxlength' => true]) ?>

    <?php if($model->isNewRecord):?>
    <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label("List the top five items in which you're interested in saving money.") ?>
    <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
    <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>