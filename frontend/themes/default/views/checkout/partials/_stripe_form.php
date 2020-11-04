<?php
use frontend\assets\StripeAsset;
use yii\web\View;

StripeAsset::register($this);
?>


<div class="pad-btm-sm">
<div class="row">
    <div class="col-xs-12 pad-btm">
        <h3>Credit/Debit Card</h3>
        <span>We accept all major credit cards</span>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-danger payment-errors hidden"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="ccname">Name on Card</label>
            <input type="text" class="form-control" data-stripe="name">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="ccnumber">Card Number</label>
            <input type="text" class="form-control" data-stripe="number">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="ccmonth">MM</label>
            <input type="text" class="form-control" data-stripe="exp_month">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="ccyear">YY</label>
            <input type="text" class="form-control" data-stripe="exp_year">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="cvc">CVC</label>
            <input type="text" class="form-control" data-stripe="exp_cvc">
        </div>
    </div>
</div>

<div class="hidden">
    <!-- hide the Stripe token validation. Yii's validation always takes precedence over Stripe events !-->
    <?= $form->field($model, 'stripe_token')->hiddenInput()->label(false); ?>
</div>


<?php
$stripe_key = '';
if( $payment_settings->stripe_test_mode ){
    if( $payment_settings->stripe_test_publishable_key ){
        $stripe_key = $payment_settings->stripe_test_publishable_key;
    }
}elseif( $payment_settings->stripe_live_publishable_key ){
    $stripe_key = $payment_settings->stripe_live_publishable_key;
}
$this->registerJs(
    "Stripe.setPublishableKey('$stripe_key');
    var form_name = $('form#form-checkout');
    function hasStripeFields(form_id){
        var stripe_fields = $('form#'+ form_id +' input[data-stripe]:enabled');
        if(stripe_fields.length > 0){
            return true;
        }
        return false;
    }

    $('form#form-checkout').bind('submit', function(e){
        if( hasStripeFields('form-checkout') ){
            e.preventDefault();
            form_name.find('button[type=\"submit\"]').prop('disabled', true);                 //disable input
            Stripe.card.createToken(form_name, stripeResponseHandler);                      //request token from Stripe
        }
    });
    function stripeResponseHandler(status, response){
        if(response.error){
            form_name.find('.payment-errors').removeClass('hidden');
            form_name.find('.payment-errors').text(response.error.message);
            form_name.find('button[type=\"submit\"]').prop('disabled', false);                // Re-enable submission
        }else{
            var token = response.id;
            form_name.find('input#checkoutform-stripe_token').val(token);                           //set token value
            form_name.get(0).submit();
        }
    };",
    View::POS_END,
    'stripe-form'
);
?>
</div>