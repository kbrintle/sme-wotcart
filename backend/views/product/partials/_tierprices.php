<?php

use common\models\catalog\CatalogProductTierPrice;
use common\components\CurrentStore;

$tiersQuery = CatalogProductTierPrice::find()->where(['product_id' => $model->id, 'store_id' => CurrentStore::getStoreId()])->orderBy(['qty' => SORT_ASC])->all();
$tiers = [];
foreach ($tiersQuery as $id => $tier) {
    $obj = new \stdClass();
    $obj->id = $tier->id;
    $obj->store_id = $tier->store_id;
    $obj->product_id = $tier->product_id;
    $obj->all_groups = $tier->all_groups;
    $obj->customer_group_id = $tier->customer_group_id;
    $obj->qty = $tier->qty;
    $obj->value = $tier->value;
    $tiers[] = $obj;
}
?>

<button type="button" id="add-tier-price" data-index=<?=count($tiers)?> class="btn btn-primary">Add Price Tier</button><br><br>
    <div id="tier-price-select" class="panel-body">
                <? foreach ($tiers as $tierKey => $tier): ?>
                <div id="option-group-<?= $tierKey ?>">
                    <input type="hidden" name="AttributeForm[tier-pricing][<?= $tierKey ?>][id]" value="<?= $tier->id ?>">
                    <span class="col-md-5">
                   if Quantity >= <input type="text" min="0" step="1" class="form-control" name="AttributeForm[tier-pricing][<?= $tierKey ?>][qty]" value="<?= $tier->qty ?>">
                    </span>
                    <span class="col-md-5">
                   Value =<input type="text" min="0.00" step="0.01" class="form-control" name="AttributeForm[tier-pricing][<?= $tierKey ?>][value]" value="<?= $tier->value ?>">
                    </span>
                </br>
                    <span class="input-group-btn"><input type="button" data-index=<? echo $tierKey ?>  class="remove-tier-price btn btn-default" value="delete"></span>
    </div></br>
                <? endforeach; ?>
    </div>