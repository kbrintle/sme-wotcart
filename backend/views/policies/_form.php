<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreBenefitPage */
/* @var $form ActiveForm */
?>
<div class="benefitspage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_1')->label("Benefit Name 1") ?>
    <?= $form->field($model, 'policy_details_1')->textarea(['rows'=>10])->label("Benefit Policy Details 1") ?>

    <?= $form->field($model, 'name_2')->label("Benefit Name 2") ?>
    <?= $form->field($model, 'policy_details_2')->textarea(['rows'=>10])->label("Benefit Policy Details 2") ?>

    <?= $form->field($model, 'name_3')->label("Benefit Name 3") ?>
    <?= $form->field($model, 'policy_details_3')->textarea(['rows'=>10])->label("Benefit Policy Details 3") ?>

    <?= $form->field($model, 'name_4')->label("Benefit Name 4") ?>
    <?= $form->field($model, 'policy_details_4')->textarea(['rows'=>10])->label("Benefit Policy Details 4") ?>

    <?= $form->field($model, 'general_policy')->label("General Policy Header") ?>
    <?= $form->field($model, 'general_policy_details')->textarea(['rows'=>10])->label("General Policy Details") ?>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- benefitspage-form -->