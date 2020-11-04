<div class="panel-body panel__ui-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'billing_first_name')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'billing_last_name')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'billing_street_address')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'billing_apartment_suite')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'billing_city')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'billing_subregion_id')->dropDownList(
                    $states, [
                    'class' => 'form-control',
                    'prompt' => 'Select State'
                ]); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'billing_zipcode')->textInput([
                    'class' => 'form-control',
                    'data-stripe' => "address_zip"
                ]); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= $form->field($model, 'billing_phone')->textInput([
                    'class' => 'form-control'
                ]); ?>
            </div>
        </div>
    </div>
</div>