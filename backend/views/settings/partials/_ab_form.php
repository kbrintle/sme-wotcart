<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsStore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'about_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'who_text')->textarea(['rows' => 6]) ?>

    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
    <?php ActiveForm::end(); ?>

</div>