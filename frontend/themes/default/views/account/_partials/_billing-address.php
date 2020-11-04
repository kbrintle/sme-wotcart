<div id="info-billing_address" class="row <?php if($model->same_as_shipping):?>hide<?php endif;?>">
    <div class="col-md-12">
        <h3>Billing Address</h3><br>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'billing_first_name')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'billing_last_name')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'billing_address_1')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'billing_address_2')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'billing_city')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'billing_subregion')->dropDownList(
            $states,
            [
                'class' => 'form-control',
                'prompt' => 'Select a State'
            ]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'billing_zipcode')->textInput(['class' => 'form-control']) ?>
    </div>
</div>