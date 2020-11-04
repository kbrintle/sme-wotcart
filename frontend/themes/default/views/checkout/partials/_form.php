<?php

use yii\widgets\ActiveForm;
use common\components\CurrentStore;
use yii\helpers\Html;
use common\models\customer\CustomerAddress;
use yii\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => 'form-checkout',
    'options' => [
        'class' => 'form',
        'enctype' => 'multipart/form-data'
    ],
    'enableAjaxValidation' => true
]);

if (count($model->errors) > 0): ?>
    <div class="col-xs-12">
        <div class="alert alert-warning">
            <ul class="list-unstyled">
                <?php foreach ($model->errors as $k => $v): ?>
                    <?php foreach ($v as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php
$states = $model->states;
if ($shipping_addresses = CustomerAddress::find()->where(['customer_id' => $model->getUser()->id, 'type' => 'shipping'])->asArray()->all()) {
    $shipping_addresses = ArrayHelper::map($shipping_addresses, "address_id", function ($model) {
        return $model['address_1'] . " " . $model['city'] . ", " . $model['region'];
    });
};

if ($billing_addresses = CustomerAddress::find()->where(['customer_id' => $model->getUser()->id, 'type' => 'billing'])->asArray()->all()) {
    $billing_addresses = ArrayHelper::map($billing_addresses, "address_id", function ($model) {
        return $model['address_1'] . " " . $model['city'] . ", " . $model['region'];
    });
};
?>

<div class="col-md-8">
    <div class="checkout-order-shipping">
        <div class="panel panel__ui">
            <div class="panel-heading panel__ui-heading">
                <h3 class="panel__ui-heading-ttl">Shipping Address</h3>
            </div>
            <?php if ($shipping_addresses): ?>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="checkoutform-fill_address">
                                Fill Shipping Address
                            </label>
                            <?= Html::dropDownList("fill_address", null, $shipping_addresses, ['data-type' => "shipping", 'class' => 'form-control', 'prompt' => [
                                'text' => 'Select Address',
                                'options' => ['disabled' => true, 'selected' => true]]]); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?= $this->render('_shipping_info', ['form' => $form, 'model' => $model, 'states' => $states]); ?>
        </div>
    </div>

    <div class="checkout-order-billing" ng-if="!billing_is_shipping">
        <div class="panel panel__ui">
            <div class="panel-heading panel__ui-heading">
                <h3 class="panel__ui-heading-ttl">Billing Address</h3>
            </div>
            <?php if ($billing_addresses): ?>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="checkoutform-fill_address">Fill Billing
                                Address</label>
                            <?= Html::dropDownList("fill_address", null, $billing_addresses, ['data-type' => "billing", 'class' => 'form-control',
                                'prompt' => ['text' => 'Select Address', 'options' => ['disabled' => true, 'selected' => true]]]); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?= $this->render('_billing_info', ['form' => $form, 'model' => $model, 'states' => $states]); ?>
        </div>
    </div>
    <?= $this->render('_reward_points', ['form' => $form, 'model' => $model]); ?>
    <?= $this->render('_payment_info', ['form' => $form, 'model' => $model]); ?>
    <?= $this->render('_customer_notes', ['form' => $form, 'model' => $model]); ?>
</div>
<div id="order_summary" class="col-md-4">
    <?= $this->render('_order_summary', ['quote' => $quote, 'form' => $form, 'model' => $model]); ?>
</div>

<input id="store-url" type="hidden" name="store-url" value="<?= CurrentStore::getStore()->url; ?>">
<?php ActiveForm::end(); ?>


