<?php

use yii\helpers\Html;
use frontend\components\Assets;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogProductGallery;
use app\components\StoreUrl;

?>

<?php if (isset($quote_item)): ?>

    <?php
    $sku = $quote_item['sku'];
    $quantity = $quote_item['qty'];
    $product_id = $quote_item['id'];
    $attribute_set = CatalogProduct::getAttributeSet($product_id);
    $product = CatalogProduct::findOne($product_id);
    ?>

    <div class="row">
        <div class="col-md-1">
            <?php
            $base_image = CatalogProductGallery::getImages($product_id);
            $image = Assets::productResource($base_image);
            ?>
            <img src="<?= $image ?>" class="zoom center-block img-responsive img-responsive-mobile"/>
        </div>
        <div class="col-md-6">
            <p class="color-darkerGray">
                <?= Html::a($quote_item['name'], StoreUrl::to('shop/products/' . $product->slug)) ?>
                <br>
                <?php foreach ($quote_item['options'] as $option): ?>
                    <?= " $option"; ?>
                <?php endforeach; ?>
            </p>
            <dl class="clearfix">
            </dl>

        </div>
        <div class="col-md-2">
        <span class="inline">
                <a class="cart-sub" data-pid="<?= $product_id; ?>" data-sku="<?= $sku; ?>"><i
                            class="material-icons color-gray align-middle margin-right">remove_circle_outline</i></a>
            <small class="color-darkGray"><span class="cart-item-count"
                                                data-pid="<?= $product_id; ?>"><?= $quantity ?></span></small>
                <a class="cart-add" data-pid="<?= $product_id; ?>" data-sku="<?= $sku; ?>"><i
                            class="material-icons color-gray align-middle margin-left">add_circle_outline</i></a>
        </span>
        </div>
        <div class="col-md-3 right-text">
            <p class="cart-item-price">$<?= $quote_item['itemsPrice'] ?></p>
            <a class="cart-remove" data-sku="<?= $sku; ?>" data-pid="<?= $product_id; ?>">
                <small>Remove</small>
            </a>
        </div>
    </div>

<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>
