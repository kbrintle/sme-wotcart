<?php

use frontend\components\Assets;
use common\models\catalog\CatalogProduct;
use app\components\StoreUrl;

$related_products = CatalogProduct::getRelatedProducts($product_id);

if ($related_products): ?>
    <section class="related">
        <div class="container">
            <div class="row pad-xs">
                <div class="col-sm-12 col-md-12 col-lg-12 clearfix">
                    <h3 class="pull-left">Related Products</h3>
                </div>
                <?php foreach ($related_products as $related_product): ?>
                    <?php $name = CatalogProduct::getName($related_product->id); ?>
                    <?php $getQuote = CatalogProduct::getAttributeValue($product_id, 'get-quote'); ?>
                    <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6">
                        <div class="product-thumb">
                            <div class="image-div">
                                <a href="<?= StoreUrl::to('shop/products/' . $related_product->slug); ?>">
                                    <?php
                                    $base_image = CatalogProduct::getGalleryImages($related_product->id, 'base-image');
                                    $image = $base_image ? Assets::productResource($base_image->value) : Assets::mediaResource('');
                                    ?>
                                    <img src="<?= $image ?>" class="center-block img-responsive"
                                         alt="<?= $name ?>">
                                </a>
                            </div>

                            <div class="thumb-footer">
                                <h5>
                                    <a href="<?= StoreUrl::to('shop/products/' . $related_product->slug); ?>">
                                        <?= CatalogProduct::getBrand($related_product->id); ?><br/>
                                        <?= $name ?><br/>
                                    </a>
                                </h5>
                            </div>
                            <div class="product-actions clearfix">
                                <form id="product-form">
                                    <div>
                                        <?php if (!isset($related_product->parent_id) && ($related_product->type !== "grouped") && !Yii::$app->user->isGuest && !$getQuote): ?>
                                            <span class="price">
                                    <span class="product-price">
                                        <?= CatalogProduct::getPriceString($related_product->id); ?>
                                    </span>
                                    </span>
                                        <?php endif; ?>

                                    </div>
                                    <div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?></div>
        </div>
    </section>
<?php endif; ?>
