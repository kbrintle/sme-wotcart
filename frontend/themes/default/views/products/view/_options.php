<?php

use common\models\catalog\CatalogProductOption;
use common\models\catalog\CatalogProductOptionValue;
use common\models\catalog\CatalogProduct;
use common\models\promotion\PromotionDiscount;
use common\components\CurrentStore;

$options = CatalogProductOption::getOptions($product_id, CurrentStore::getStoreId());

$discountPercent = 1;
if ($discount = PromotionDiscount::productHasTargetedDiscount($product_id)) {
        if ($discount->type == "percent") {
            if($discount->amount == 100){
                $discountPercent = 0;
            }else{
            $discountPercent = $discount->amount/100;
            }
        }
}
?>
<?php if($options):?>
<div class="form-group quantity m-b--sm">
    <label>Quantity</label>
    <input id="quantity" class="text-center form-control m-b--sm" type="text" data-sku="<?=CatalogProduct::getSku($product_id)?>" data-pid="<?=$product_id?>" name="qty[]" value="1">
</div>
    <div id="options" class="custom-options" data-delimiter="<?= Yii::$app->params['options-sku-delimiter']?>">
        <?php foreach ($options as $option):
            $optionValues = CatalogProductOption::getOptionValues($option->option_id); ?>
            <label> <?= $option->title; ?> </label>
            <?php if($option->type == CatalogProductOption::TYPE_DROPDOWN):?>
    <div class="option-group" id="<?=$option->option_id?>" isrequired="<?=$option->is_required?>" type="<?=$option->type?>">
        <select name="<?=$option->option_id?>" class="form-control custom-option-sel" >
            <option name="null" data-sku="" data-price="0.00">--Select One--</option>
            <?php foreach ($optionValues as $optionValue):?>
                <option data-sku="<?=$optionValue->sku?>" data-price="<?=$optionValue->price * $discountPercent?>">
                    <?= $optionValue->title ?>
                </option>
            <?php endforeach; ?>
        </select></div>
            <?php elseif($option->type == CatalogProductOption::TYPE_RADIO):?>
            <div class="option-group" id="<?=$option->option_id?>" isrequired="<?=$option->is_required?>" type="<?=$option->type?>">
                <?php foreach ($optionValues as $optionValue):?>
                    <div class="radio">
                        <input  type="radio" name="<?=$option->option_id?>" class="custom-option-sel" data-sku="<?=$optionValue->sku?>" data-price="<?=$optionValue->price * $discountPercent?>">
                        <label><?= $optionValue->title ?> </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php elseif($option->type == CatalogProductOption::TYPE_CHECKBOX):?>
            <div class="option-group" id="<?=$option->option_id?>" isrequired="<?=$option->is_required?>" type="<?=$option->type?>">
                <?php foreach ($optionValues as $optionValue):?>
                    <div class="checkbox">
                        <input type="checkbox" class="custom-option-sel" name="<?=$option->option_id?>" class="" data-sku="<?=$optionValue->sku?>" data-price="<?=$optionValue->price * $discountPercent?>">
                        <label><?= $optionValue->title ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <br>
            <?php endforeach; ?>
</div>
<?php endif; ?>




