<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\sales\ShippingTableRate;
use common\components\helpers\FormHelper;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsShipping */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-shipping-form">

    <?php $form = ActiveForm::begin([
        'id'=>'SettingsShipping'
    ]); ?>

    <?= $form->field($model, 'free_shipping')->dropDownList(FormHelper::getBooleanValues(false))->label("Do you offer Free Shipping with Minimum Purchase?"); ?>

    <div class="settings-free-ship-min hidden">
        <?= $form->field($model, 'free_shipping_min')->textInput(['maxlength' => true])->label("Minimum Purchase for Free Shipping") ?>
    </div>

    <br>
    <?php

    $ratesQuery = ShippingTableRate::find()->where(['store_id' => CurrentStore::getStoreId()])->orderBy(['price' => SORT_ASC])->all();
    $rates = [];

    foreach ($ratesQuery as $id => $rate) {
        $obj = new \stdClass();
        $obj->id = $rate->id;
        $obj->store_id = $rate->store_id;
        $obj->price = $rate->price;
        $obj->cost = $rate->cost;
        $rates[] = $obj;
    }
    ?>

    <button type="button" id="add-shipping-rate" data-index=<?=count($rates)?> class="btn btn-primary">Add Flat Shipping Rate</button><br><br>
    <div id="shipping-rate-select" class="panel-body">
        <? foreach ($rates as $rateKey => $rate): ?>
            <div id="rate-group-<?= $rateKey ?>">
                <input type="hidden" name="ShippingRates[<?= $rateKey ?>][id]" value="<?= $rate->id ?>">
                <span class="col-md-5">
                   if Total >= <input type="text" min="0" step="1" class="form-control" name="ShippingRates[<?= $rateKey ?>][price]" value="<?= $rate->price ?>">
                    </span>
                <span class="col-md-5">
                   Shipping =<input type="text" min="0.00" step="0.01" class="form-control" name="ShippingRates[<?= $rateKey ?>][cost]" value="<?= $rate->cost ?>">
                    </span>
                </br>
                <span class="input-group-btn"><input type="button" data-index=<? echo $rateKey ?>  class="remove-shipping-rate btn btn-default" value="delete"></span>
            </div></br>
        <? endforeach; ?>
    </div>

    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>

    <?php ActiveForm::end(); ?>

</div>