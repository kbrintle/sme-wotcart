<?php

use yii\bootstrap\ActiveForm;
use common\models\core\CountryRegion;

?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'form-address-delete',
        'options' => [
            'class' => 'form',
            'enctype' => 'multipart/form-data'
        ],
        'enableAjaxValidation' => true
    ]); ?>
    <input type="hidden" id="customeraddress-address_id" class="form-control" name="CustomerAddress[action]"
           value="delete">
    <?= $form->field($model, 'address_id')->hiddenInput()->label(false); ?>
    <div class="modal-body text-center">
        <p class="text-right">
        <h3>Are you sure you wish to delete:</h3></p>
        <p>
        <h3><?= $model->address_1 ?></h3>
        </p>
        <p>
        <h3><?= $model->address_2 ?></h3>
        </p>
        <p>
        <h3><?= "$model->city" . " " . CountryRegion::getRegionById($model->region_id)->code ?>?</h3>
        </p>
        </p>
    </div>
    <div class="modal-footer">

        <button type="button" class="btn btn-success" data-dismiss="modal">No
        </button>
        <button type="submit" class="btn btn-danger" type="submit">Yes</button>
        <?php ActiveForm::end(); ?>
    </div>