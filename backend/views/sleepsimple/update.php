<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Sleep Simple Product Finder Question';
?>

<div class="container-fluid store-index pad-top">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row action-row">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?= $this->render('partials/_form', [
        'model' => $model,
        'form'  => $form
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>