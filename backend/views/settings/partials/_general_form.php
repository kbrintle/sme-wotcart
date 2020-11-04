<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsStore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-store-form">

    <?php $form = ActiveForm::begin(['id' => 'SettingsStore']); ?>
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>


    <div class="pad-top">
        <h4>Contact</h4>
        <?= $form->field($model, 'address')->textInput() ?>

        <?= $form->field($model, 'city')->textInput() ?>

        <?= $form->field($model, 'state')->dropdownList(ArrayHelper::map(FormHelper::getUSStates(), 'abbreviation', 'abbreviation'), ['prompt' => 'Select one']) ?>

        <?= $form->field($model, 'zipcode')->textInput() ?>

        <?= $form->field($model, 'phone')->textInput()->label("Customer Service Telephone") ?>
    </div>

    <div class="pad-top">
        <h4>Emails</h4>
        <?= $form->field($model, 'general_email')->textInput() ?>

        <?= $form->field($model, 'sales_email')->textInput() ?>

    </div>
    <div class="pad-top">
        <h4>Supervisor</h4>
        <?= $form->field($model, 'supervisor_active')->dropdownList(['1' => 'Yes', '0' => 'No'], ['prompt' => 'Select one']) ?>

        <?= $form->field($model, 'supervisor_order_threshold')->textInput() ?>

        <?= $form->field($model, 'supervisor_email')->textInput() ?>
    </div>
    <div class="pad-top">
        <h4>Site Wide Scripts</h4>
        <?php if (\backend\components\CurrentUser::isAdmin()): ?>
            <?= $form->field($model, 'misc_header_scripts')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'misc_footer_scripts')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'misc_success_scripts')->textarea(['rows' => 6]) ?>
        <?php endif; ?>
    </div>
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
    <?php ActiveForm::end(); ?>

</div>