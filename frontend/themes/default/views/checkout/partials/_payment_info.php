<?php
$payment_settings = $model->paymentSettings;

?>

<div class="checkout-order-payment">
    <div class="panel panel__ui">
        <div class="panel-heading panel__ui-heading clearfix">
            <h3 class="panel__ui-heading-ttl pull-left">Payment Info</h3>
            <div class="secure-msg pull-right">
                <i class="material-icons">lock_outline</i>
                <span>Secure and encrypted</span>
            </div>
        </div>
        <div class="panel-body panel__ui-body">
<!--            --><?php //if( $payment_settings->purchase_order_enabled ): ?>
                <?= $this->render('_po', [
                    'form'              => $form,
                    'model'             => $model
                ]); ?>
<!--            --><?php //endif; ?>
            <?php if($payment_settings): ?>
                <?php if( $payment_settings->stripe_enabled ): ?>
                    <?= $this->render('_stripe_form', [
                            'form'              => $form,
                            'model'             => $model,
                            'payment_settings'  => $payment_settings
                        ]); ?>
                <?php endif; ?>

                <?php if( $payment_settings->cardconnect_enabled ): ?>
                    <?= $this->render('_cc_form', [
                        'form'              => $form,
                        'model'             => $model,
                        'payment_settings'  => $payment_settings
                    ]); ?>
                <?php endif; ?>

                <?php if( $payment_settings->paypal_enabled ): ?>
                    <?= $this->render('_paypal_form', [
                            'form'              => $form,
                            'model'             => $model
                        ]); ?>
                <?php endif; ?>
<!--            --><?php //else: ?>
<!--                <p>No payment settings configured</p>-->
            <?php endif; ?>
        </div>
    </div>
</div>