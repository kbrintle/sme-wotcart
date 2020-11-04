<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">Edit Address</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin([
        'id' => 'form-address',
        'options' => [
            'class' => 'form',
            'enctype' => 'multipart/form-data'
        ],
        'enableAjaxValidation' => true
    ]); ?>

    <?= $form->field($model, 'address_id')->hiddenInput()->label(false); ?>
    <input type="hidden" id="customeraddress-address_id" class="form-control" name="CustomerAddress[action]"
           value="save">
    <div class="panel-body panel__ui-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'firstname')->textInput([
                        'class' => 'form-control'
                    ]); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'lastname')->textInput([
                        'class' => 'form-control'
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'address_1')->textInput([
                        'class' => 'form-control',
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'address_2')->textInput([
                        'class' => 'form-control'
                    ])->label("Shipping Apartment Suite"); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'city')->textInput([
                        'class' => 'form-control',
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'region_id')->dropDownList(
                        $states, [
                        'class' => 'form-control shipping-subregion',
                        'prompt' => 'Select State'
                    ])->label("State"); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'postcode')->textInput([
                        'class' => 'form-control',
                        'data-stripe' => "address_zip"
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'phone')->textInput([
                        'class' => 'form-control'
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?= $form->field($model, 'type')->dropDownList([
                        'shipping' => 'Shipping',
                        'billing' => 'Billing'
                    ]); ?>
                </div>
            </div>
            <div style="margin-top:18px;">
                <div class="col-md-3">
                    <div class="form-group">
                        <?= $form->field($model, 'default_shipping')->checkbox(); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?= $form->field($model, 'default_billing')->checkbox(); ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <?= Html::submitButton($isNewRecord ? 'Create' : 'Update', ['form' => "form-address", 'class' => 'btn btn-primary pull-right']); ?>
</div>

<?php ActiveForm::end(); ?>

