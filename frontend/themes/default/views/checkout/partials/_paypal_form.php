<div class="row">
    <div class="col-xs-12 pad-btm">
        <h3>Credit/Debit Card</h3>
        <span>We accept all major credit cards</span>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'paypal_card_first_name')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'paypal_card_last_name')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'paypal_card_number')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'paypal_exp_month')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'paypal_exp_year')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'paypal_cvc')->textInput([
                'class' => 'form-control'
            ]); ?>
        </div>
    </div>
</div>
