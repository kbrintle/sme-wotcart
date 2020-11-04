<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'file')->fileInput()->label('Select File') ?>

    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end() ?>