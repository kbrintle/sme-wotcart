<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsStore */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'id'=>'SettingsPayment'
]); ?>
<!-- stripe START !-->
<div class="row">
    <div class="col-xs-12">
        <h4>Tax </h4>
        <?= $form->field($model, 'calculate_tax')->dropDownList(['0'=>'Disabled', '1'=>'Enabled'], []) ?>
    </div>
    <div class="col-xs-12">
        <h4>Purchase Order </h4>
        <?= $form->field($model, 'purchase_order_enabled')->dropDownList(['1'=>'Enabled', '0'=>'Disabled'], []) ?>

    </div>
<!--    <div class="col-xs-12">-->
<!--        <h4>Stripe</h4>-->
<!---->
<!--        <div class="row">-->
<!--            <div class="col-xs-12 col-md-6">-->
<!--                --><?//= $form->field($model, 'stripe_enabled')->checkbox([
//                    'ng-init'   => 'stripe_enabled = ('.$model->stripe_enabled.' == 1) ? true : false',
//                    'ng-model'  => 'stripe_enabled',
//                    'ng-change' => 'changePaymentMethod("stripe_enabled")',
//                    'labelOptions' => [
//                        'class' => 'checkbox-inline'
//                    ]
//                ], true); ?>
<!--            </div>-->
<!--            <div class="col-xs-12 col-md-6">-->
<!--                --><?//= $form->field($model, 'stripe_test_mode')->checkbox([
//                    'ng-disabled' => '!stripe_enabled',
//                    'labelOptions' => [
//                        'class' => 'checkbox-inline'
//                    ]
//                ], true); ?>
<!--            </div>-->
<!--        </div>-->
<!---->
<!---->
<!--        <div class="row">-->
<!--            <div class="col-xs-12">-->
<!--                --><?//= $form->field($model, 'stripe_test_publishable_key')->textInput(['ng-disabled' => '!stripe_enabled','class'=>'form-control']); ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="row">-->
<!--            <div class="col-xs-12">-->
<!--                --><?//= $form->field($model, 'stripe_test_secret_key')->textInput(['ng-disabled' => '!stripe_enabled','class'=>'form-control']); ?>
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="row">-->
<!--            <div class="col-xs-12">-->
<!--                --><?//= $form->field($model, 'stripe_live_publishable_key')->textInput(['ng-disabled' => '!stripe_enabled','class'=>'form-control']); ?>
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="row">-->
<!--            <div class="col-xs-12">-->
<!--                --><?//= $form->field($model, 'stripe_live_secret_key')->textInput(['ng-disabled' => '!stripe_enabled','class'=>'form-control']); ?>
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="col-xs-12">
        <h4>Card Connect</h4>
        <?= $form->field($model, 'cardconnect_enabled')->dropDownList(['1'=>'Enabled', '0'=>'Disabled'], []) ?>
    </div>
</div>
<!-- stripe END !-->

<?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>

<?php ActiveForm::end(); ?>
<!-- paypal END !-->