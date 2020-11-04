<div class="panel-body panel__ui-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'shipping_first_name')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'shipping_last_name')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'shipping_street_address')->textInput([
                    'class' => 'form-control',
                    'data-stripe' => "address_line1"
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'shipping_apartment_suite')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'shipping_city')->textInput([
                    'class' => 'form-control',
                    'data-stripe' => "address_city"
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'shipping_subregion_id')->dropDownList(
                    $states, [
                    'class' => 'form-control shipping-subregion',
                    'prompt' => 'Select State'
                ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'shipping_zipcode')->textInput([
                    'class' => 'form-control',
                    'data-stripe' => "address_zip"
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'shipping_phone')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="checkbox"
                 ng-init="billing_is_shipping=<?php echo $model->billing_is_shipping ? 'true' : 'false'; ?>">
                <?= $form->field($model, 'billing_is_shipping')->checkbox([
                    'ng-model' => 'billing_is_shipping'
                ]); ?>
            </div>
        </div>
    </div>
</div>
