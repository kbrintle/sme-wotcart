<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'shipping_description')->textInput() ?>

    <?= $form->field($model, 'is_virtual')->textInput() ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'coupon_code')->textInput() ?>

    <?= $form->field($model, 'discount_description')->textInput() ?>

    <?= $form->field($model, 'discount_amount')->textInput() ?>

    <?= $form->field($model, 'discount_canceled')->textInput() ?>

    <?= $form->field($model, 'discount_invoiced')->textInput() ?>

    <?= $form->field($model, 'discount_refunded')->textInput() ?>

    <?= $form->field($model, 'grand_total')->textInput() ?>

    <?= $form->field($model, 'shipping_amount')->textInput() ?>

    <?= $form->field($model, 'shipping_canceled')->textInput() ?>

    <?= $form->field($model, 'shipping_invoiced')->textInput() ?>

    <?= $form->field($model, 'subtotal_canceled')->textInput() ?>

    <?= $form->field($model, 'subtotal_invoiced')->textInput() ?>

    <?= $form->field($model, 'subtotal_refunded')->textInput() ?>

    <?= $form->field($model, 'tax_amount')->textInput() ?>

    <?= $form->field($model, 'tax_canceled')->textInput() ?>

    <?= $form->field($model, 'tax_invoiced')->textInput() ?>

    <?= $form->field($model, 'tax_refunded')->textInput() ?>

    <?= $form->field($model, 'total_canceled')->textInput() ?>

    <?= $form->field($model, 'total_invoiced')->textInput() ?>

    <?= $form->field($model, 'total_offline_refunded')->textInput() ?>

    <?= $form->field($model, 'total_online_refunded')->textInput() ?>

    <?= $form->field($model, 'total_paid')->textInput() ?>

    <?= $form->field($model, 'total_qty_ordered')->textInput() ?>

    <?= $form->field($model, 'total_refunded')->textInput() ?>

    <?= $form->field($model, 'email_sent')->textInput() ?>

    <?= $form->field($model, 'quote_id')->textInput() ?>

    <?= $form->field($model, 'quote_address_id')->textInput() ?>

    <?= $form->field($model, 'billing_address_id')->textInput() ?>

    <?= $form->field($model, 'shipping_address_id')->textInput() ?>

    <?= $form->field($model, 'adjustment_negative')->textInput() ?>

    <?= $form->field($model, 'adjustment_positive')->textInput() ?>

    <?= $form->field($model, 'payment_authorization_amount')->textInput() ?>

    <?= $form->field($model, 'shipping_tax_refunded')->textInput() ?>

    <?= $form->field($model, 'shipping_refunded')->textInput() ?>

    <?= $form->field($model, 'shipping_tax_amount')->textInput() ?>

    <?= $form->field($model, 'shipping_discount_amount')->textInput() ?>

    <?= $form->field($model, 'shipping_method')->textInput() ?>

    <?= $form->field($model, 'shipping_incl_tax')->textInput() ?>

    <?= $form->field($model, 'can_ship_partially_item')->textInput() ?>

    <?= $form->field($model, 'can_ship_partially')->textInput() ?>

    <?= $form->field($model, 'subtotal_incl_tax')->textInput() ?>

    <?= $form->field($model, 'total_due')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'customer_group_id')->textInput() ?>

    <?= $form->field($model, 'customer_is_guest')->textInput() ?>

    <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'customer_note_notify')->textInput() ?>

    <?= $form->field($model, 'customer_email')->textInput() ?>

    <?= $form->field($model, 'customer_firstname')->textInput() ?>

    <?= $form->field($model, 'customer_lastname')->textInput() ?>

    <?= $form->field($model, 'customer_middlename')->textInput() ?>

    <?= $form->field($model, 'customer_prefix')->textInput() ?>

    <?= $form->field($model, 'customer_suffix')->textInput() ?>

    <?= $form->field($model, 'customer_taxvat')->textInput() ?>

    <?= $form->field($model, 'hold_before_state')->textInput() ?>

    <?= $form->field($model, 'hold_before_status')->textInput() ?>

    <?= $form->field($model, 'order_currency_code')->textInput() ?>

    <?= $form->field($model, 'remote_ip')->textInput() ?>

    <?= $form->field($model, 'store_name')->textInput() ?>

    <?= $form->field($model, 'customer_note')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'total_item_count')->textInput() ?>

    <?= $form->field($model, 'paypal_ipn_customer_notified')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>