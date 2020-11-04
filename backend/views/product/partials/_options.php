<?php

use common\models\catalog\CatalogProductOption;
use common\models\catalog\CatalogProductOptionValue;
use common\components\CurrentStore;



$productOptions = [];
$skus = [];
if($model->has_options) {
    $optionsQuery = CatalogProductOption::find()->where(['product_id' => $model->id])->andWhere(['IN', 'store_id', [CurrentStore::getStoreId(), 0]])->orderBy(['sort_order' => SORT_ASC])->all();
    foreach ($optionsQuery as $id => $option) {
        $obj = new \stdClass();
        $obj->option_id = $option->option_id;
        $obj->is_required = $option->is_required;
        $obj->sku = $option->sku;
        $obj->type = $option->type;
        $obj->sort_order = $option->sort_order;
        $obj->title = $option->title;

        $optionValuesQuery = CatalogProductOptionValue::find()->where(['option_id' => $option->option_id])->andWhere(['store_id' => CurrentStore::getStoreId()])->orderBy(['sort_order' => SORT_ASC])->all();
        foreach ($optionValuesQuery as $id => $optionValue) {
            $objValue = new \stdClass();
            $objValue->option_value_id = $optionValue->option_value_id;
            $objValue->title = $optionValue->title;
            $objValue->sku = $optionValue->sku;
            $objValue->price = $optionValue->price;
            $objValue->price_type = $optionValue->price_type;
            $objValue->sort_order = $optionValue->sort_order;
            $obj->values[] = $objValue;
            $skus[] = $optionValue->sku;
        }

        if (CurrentStore::getStoreId() != CurrentStore::ALL) {
            $optionValuesQuery = CatalogProductOptionValue::find()->where(['option_id' => $option->option_id])->andWhere(['store_id' => '0'])->andWhere(['NOT IN', 'sku', $skus])->orderBy(['sort_order' => SORT_ASC])->all();
            foreach ($optionValuesQuery as $id => $optionValue) {
                $objValue = new \stdClass();
                $objValue->option_value_id = $optionValue->option_value_id;
                $objValue->title = $optionValue->title;
                $objValue->sku = $optionValue->sku;
                $objValue->price = $optionValue->price;
                $objValue->price_type = $optionValue->price_type;
                $objValue->sort_order = $optionValue->sort_order;
                $obj->values[] = $objValue;
            }
        }
        if (isset($obj->value)) {
            uasort($obj->values, "common\models\catalog\CatalogProductOption::sort_order");
        }
        $productOptions[] = $obj;
    }
}
?>


<button type="button" id="add-product-custom-option" data-index=<?=count($productOptions)?> class="btn btn-primary">Add Option</button><br><br>
<div id="select-attribute" class="panel panel-default">
    <div class="panel-body">
        <div id="select-options">
            <div id="sortable" class="ui-sortable top">
                <? foreach ($productOptions as $optKey => $option): ?>
                <div id="option-group-<?= $optKey ?> class="panel panel-default">
                <div class="panel panel-body panel-default">
                    <div class="input-group"><span class="input-group-btn sortable_handler"><i class="material-icons">drag_handle</i></span>
                        <input type="hidden" name="AttributeForm[options][<?= $optKey ?>][option_id]" value = "<?=$option->option_id?>">
                        <span class="col-md-2">Title<input type="text" class="form-control" value = "<?=$option->title?>" name="AttributeForm[options][<?= $optKey ?>][title]" placeholder="Title"></span>
                        <span class="col-md-2">Input Type<select class="form-control" name="AttributeForm[options][<?= $optKey ?>][type]" placeholder="Input Type" value = "<?=$option->type?>">
                </optgroup><optgroup label="Select">
                <option <?=($option->type == 'drop_down' ? 'selected="selected"' : '')?> value="dropdown">Drop-down</option>
                <option <?=($option->type == 'radio' ? 'selected="selected"' : '')?> value="radio">Radio Buttons</option>
                <option <?=($option->type == 'checkbox' ? 'selected="selected"' : '')?> value="checkbox">Checkbox</option>
                <!--<option <?/*=($option->type == 'multiple' ? 'selected="selected"' : '')*/?> value="multiple">Multiple Select</option>-->
                </optgroup>
                </select></span>
                        <span class="col-md-2">Is Required:<select class="form-control" name="AttributeForm[options][<?= $optKey ?>][is_required]" placeholder="Is Required">
                                <option <?=($option->is_required == false ? 'selected="selected"' : '')?> value="0">No</option>
                                <option <?=($option->is_required == true ? 'selected="selected"' : '')?> value="1">Yes</option>
                            </select></span>
                        <span class="col-md-2"><input type="button" id="add-product-row" data-option-index=<?echo $optKey?> data-row-index=<?= isset($option->values) ? count($option->values) : 0?> class="btn btn-primary margin-top-20" value="Add Row"></span>
                        <span class="input-group-btn"><input type="button" id="remove-option" data-index=<?echo $optKey?>  class="btn btn-default" value = "delete"></span>
                    </div>
                    <div class="panel-body">
                        <div id="options-rows-<?=$optKey?>"><div id="sortable" class="ui-sortable row">
                                <? if(isset($option->values)):
                                     foreach ($option->values as $valKey => $value): ?>
                                        <div class="input-group" id="option-row-<?=$valKey?>"><span class="input-group-btn sortable_handler"><i class="material-icons">drag_handle</i></span>
                                            <input type="hidden" name="AttributeForm[options][<?= $optKey ?>][values][<?=$valKey?>][option_value_id]" value = "<?=$value->option_value_id?>">
                                            <span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][<?= $optKey ?>][values][<?=$valKey?>][title]" value = '<?=$value->title?>' placeholder="Option Title"></span>
                                            <span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][<?= $optKey ?>][values][<?=$valKey?>][price]" value = '<?=$value->price?>' placeholder="Option Price"></span>
                                            <span class = "col-md-3"><input type="text" class="form-control" name="AttributeForm[options][<?= $optKey ?>][values][<?=$valKey?>][sku]" value = '<?=$value->sku?>' placeholder="Option Sku"></span>
                                            <span class="input-group-btn"><button type="button" id="remove-option-row" data-index=<?=$valKey?> class="btn btn-default"><i class="material-icons">delete</i></button></span></div>
                                    <? endforeach; ?>
                                <? endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
</div>

