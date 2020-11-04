<?php

use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogProductGallery;

$outofstock = CatalogProduct::getAttributeValue($model->id, 'out-of-stock');
$getQuote = CatalogProduct::getAttributeValue($model->id, 'get-quote');
?>

<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-6">
    <div class="product-thumb">
        <div class="image-div">
<!--            --><?php //if (!Yii::$app->user->isGuest && !$getQuote) : ?>
<!--                <div class="favorite">-->
<!--                    <a href="#" class="modal-click category-favorite btn btn-icon-fav"-->
<!--                       data-url="--><?//= StoreUrl::to('favorites/category-modal'); ?><!--"-->
<!--                       data-sku="--><?//= $model->findAttributeValue('sku'); ?><!--"-->
<!--                       data-toggle="modal" data-target="#favoritesModal">-->
<!--                        <i class="material-icons">-->
<!--                            favorite-->
<!--                        </i>-->
<!--                    </a>-->
<!--                </div>-->
<!--            --><?php //endif; ?>
            <?php if (CatalogProduct::isNewBanner($model->id)): ?>
                <span class="label label-new">New</span>
            <?php endif; ?>
            <?php if (CatalogProduct::isSaleBanner($model->id)): ?>
                <span class="label label-sale">Sale</span>
            <?php endif; ?>
            <?php
            $base_image = CatalogProductGallery::getImages($model->id);
            $image = Assets::productResource($base_image);
            ?>
            <a href="<?= StoreUrl::to("/shop/products/$model->slug"); ?>">
                <img src="<?= $image ?>" class="center-block img-responsive img-responsive-mobile"/>
            </a>
        </div>
        <div class="thumb-footer">
            <h5>
                <a href="<?= StoreUrl::to("/shop/products/$model->slug"); ?>">
                    <?=CatalogProduct::getName($model->id)?>
                </a>
            </h5>
        </div>

        <div class="product-actions clearfix">
            <?php if (!Yii::$app->user->isGuest): ?>
            <form id="product-form" data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                <?php if ($model->type != CatalogProduct::GROUPED && !$getQuote): ?>
                    <div id="itemPrice" class="pull-left price" data-price="<?= str_replace(',', '', CatalogProduct::getPriceString($model->id, false)); ?>">
                        <?= CatalogProduct::getPriceHtml($model->id, false, true) ?>
                    </div>
                <?php endif; ?>
                <div class="pull-right">
                    <?php if (!$outofstock && !$getQuote): ?>
                    <a id="addCart"
                       class="cart-add btn btn-icon-cart pull-right"
                       data-sku="<?=CatalogProduct::getSku($model->id)?>"
                       data-pid="<?=$model->id?>">
                        <i class="material-icons">add_shopping_cart</i>
                    </a>
                    <?php endif; ?>
                    <?php if ($getQuote): ?>
                    <a class="modal-click btn btn-icon-cart pull-right"
                       ng-if="product.attributes['get-quote'].value == 1"
                       href="#"
                       data-url="<?=StoreUrl::to('quote/modal');?>"
                       data-toggle="modal" data-target="#getQuoteModal"
                       data-sku="<?=CatalogProduct::getSku($model->id)?>" data-pid="<?=$model->id?>">
                        Get Quote</a>
                    <?php endif; ?>
                </div>

            </form>
            <?php endif; ?>
        </div>
    </div>
</div>