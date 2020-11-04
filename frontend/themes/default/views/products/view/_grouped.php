<?php

use common\models\catalog\CatalogProduct;
$associatedProducts = CatalogProduct::getGroupedChildren($product->id);
?>

<div id="grouped" class="grouped-items">
    <table class="table">
        <thead>
        <td>
            Name
        </td>
        <?php if(!$getQuote): ?>
        <td>
            Price
        </td>
        <?php endif;?>
        <td>
            Qty
        </td>
        </thead>
        <tbody>
        <?php foreach ($associatedProducts as $_item): ?>
            <tr>
                <td>
                    <?= CatalogProduct::getAttributeValue($_item->id, 'name'); ?>
                    <?= $this->render('_tiered', ['product_id' => $_item->id]);?>
                </td>
                <?php if(!$getQuote): ?>
                <td class="price">
                    <?= CatalogProduct::getPriceHtml($_item->id, false, true, true) ?>
                </td>
                <?php endif;?>
                <td width="64px">
                    <input class="grouped-item-sel text-center form-control" type="text" data-pid="<?=$_item->id?>" data-sku="<?=CatalogProduct::getSku($_item->id)?>" name="qty[]" value="0">
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
