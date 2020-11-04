<?php

use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\catalog\CatalogProduct;

$getQuote = CatalogProduct::getAttributeValue($model->id, 'get-quote');
?>

<div class="row search_panel search_panel-product">
    <div class="col-md-12 bg-white">
        <div class="col-md-3">
            <?php
            $base_image = CatalogProduct::getGalleryImages($model->id, 'base-image');
            $image = $base_image ? Assets::productResource($base_image->value) : Assets::mediaResource('');
            ?>
            <a href="<?= StoreUrl::to("/shop/products/$model->slug"); ?>">
                <img src="<?= $image ?>" class="center-block img-responsive img-responsive-mobile"/>
            </a>

        </div>
        <div class="col-md-6">
            <h3 class="color-darkerBlue bold">
                <a class="color-darkerBlue"
                   href="<?= StoreUrl::to("/shop/products/$model->slug"); ?>"><?= $model->findAttributeValue('name'); ?></a>
                <?php if (!Yii::$app->user->isGuest && !$getQuote) : ?>
                    <div class="favorite">
                        <a href="#" class="modal-click category-favorite btn btn-icon-fav"
                           data-url="<?= StoreUrl::to('favorites/category-modal'); ?>"
                           data-sku="<?= $model->findAttributeValue('sku'); ?>"
                           data-toggle="modal" data-target="#favoritesModal">
                            <i class="material-icons">
                                favorite
                            </i>
                        </a>
                    </div>
                <?php endif; ?>
            </h3>
            <p class="sm-buffer small">
                <?= $model->findAttributeValue('short-description'); ?>
            </p>
        </div>
        <div class="col-md-3 center top-margin-sm"><h2 class="color-darkerBlue">
                <?php if (!Yii::$app->user->isGuest && !$getQuote && $model->type !== CatalogProduct::GROUPED) : ?>
                    <?= CatalogProduct::getPriceHtml($model->id, false, true) ?>
                <?php else: ?>
                    <br>
                <?php endif; ?>
            </h2>
            <a href="<?= StoreUrl::to("/shop/products/$model->slug"); ?>" class="btn btn-long btn-primary top-margin">
                View Details
            </a>
            <?php if (!Yii::$app->user->isGuest && !$getQuote && !CatalogProduct::hasOptions($model->id)) : ?>
                <a class="cart-add btn btn-long btn-primary top-margin"
                   data-action="<?php echo StoreUrl::to('cart/process'); ?>"
                   data-sku="<?= $model->findAttributeValue('sku'); ?>" data-pid="<?= $model->id ?>">Add to Cart</a>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="modal modal-xs fade" id="favoritesModal" tabindex="-1" role="dialog"></div>