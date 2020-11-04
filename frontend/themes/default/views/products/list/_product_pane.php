<?php
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogProduct;
use app\components\StoreUrl;
use yii\helpers\Html;
use frontend\components\Assets;
?>

<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-6">
    <div class="product-thumb">
        <?php if (CatalogProduct::getWebOnly($product->id)): ?>
            <span class="label label-web">Web Only</span>
        <?php endif; ?>
        <?php if (CatalogProduct::isOnSale($product->id)): ?>
            <span class="label label-sale">Sale</span>
        <?php endif; ?>
        <div class="image">
            <a href="<?php echo StoreUrl::to('shop/products/'. $product->slug); ?>">
                <?php
                    $base_image = CatalogProductGallery::getImages($product_id);
                    $image = Assets::productResource($base_image);
                ?>
                <img src="<?php echo $image ?>"  class="zoom center-block img-responsive img-responsive-mobile"/>
            </a>
        </div>
        <div>
            <div class="caption">
                <h4>
                    <a href="<?php echo StoreUrl::to('shop/products/'. $product->slug); ?>">
                        <?= CatalogProduct::getBrand($product->id); ?><br />
                        <?= CatalogProduct::getAttributeValue($product->id, 'name'); ?><br />
                        <?= $catalog_value ? $catalog_value->value : ''; ?>
                    </a>
                </h4>
                
            </div>
            <p class="price">
                <?php echo CatalogProduct::getPriceString($product->id, true); ?>
            </p>
        </div>
    </div>
</div>