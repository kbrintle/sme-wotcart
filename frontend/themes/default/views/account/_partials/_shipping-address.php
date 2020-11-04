<div class="row">
    <div class="col-md-12">
    <h3 id="shipping-toggle">Address</h3><br>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'address_1')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'address_2')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'city')->textInput(['class' => 'form-control']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'subregion')->dropDownList(
            $states,
            [
                'class' => 'form-control',
                'prompt' => 'Select a State'
            ]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'zipcode')->textInput(['class' => 'form-control']) ?>
    </div>
</div>