<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\models\catalog\CatalogProduct;
use frontend\components\Assets;
use common\models\catalog\CatalogProductAttachment;
use common\models\catalog\CatalogProductGallery;

$attachments = CatalogProductAttachment::getAttachments($product_id);
$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$this->title = CatalogProduct::getName($product_id);

if (count($crumb) > 0) {
    $this->params['breadcrumbs'][] = ['label' => $crumb['name'], 'url' => [\app\components\StoreUrl::to('/shop/category/' . $crumb['url'])]];

}
$getQuote = CatalogProduct::getAttributeValue($product_id, 'get-quote');
$outofstock = CatalogProduct::getAttributeValue($product_id, 'out-of-stock');
$tobigtoship = CatalogProduct::getAttributeValue($product_id, 'to-big-to-ship');

$this->params['breadcrumbs'][] = $this->title;
$sku = CatalogProduct::getAttributeValue($product_id, 'sku');
?>

<section class="product product-details pad-lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-md-7 col-lg-7">
                <div class="product-gallery">
                    <div class="product-gallery--img">
                        <?php if (CatalogProduct::isNewBanner($product->id)): ?>
                            <span class="label label-new">New</span>
                        <?php endif; ?>
                        <?php if (CatalogProduct::isSaleBanner($product->id)): ?>
                            <span class="label label-sale">Sale</span>
                        <?php endif; ?>
                        <?php
                        $base_image = CatalogProductGallery::getImages($product_id);
                        $image = Assets::productResource($base_image);
                        ?>
                        <img src="<?= $image ?>" class="zoom center-block img-responsive img-responsive-mobile"/>
                    </div>

                </div>
                <div class="product-gallery--thumbs flexslider carousel">
                    <ul class="slides">
                        <?php
                        $gallery_images = CatalogProduct::getGalleryImages($product_id);
                        if (!empty($gallery_images)):
                            foreach ($gallery_images as $image):?>
                                <li class="">
                                    <a>
                                        <img src="<?= Assets::productResource($image->value) ?>"/>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </div>

            </div>
            <div class="col-sm-4 col-sm-offset-1 col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1">
                <div class="product-info">
                    <div class="product-info--meta">
                        <div class="info-meta--top">
                            <div class="product-availability">
                                <?php if (!$outofstock): ?>
                                    <i class="material-icons check">check_circle</i>
                                    <span>Available</span>
                                <?php else: ?>
                                    <i class="material-icons check">highlight_off</i>
                                    <span>Unavailable</span>
                                <?php endif; ?>


                            </div>

                            <?php if (!Yii::$app->user->isGuest): ?>
                                <?php if ($tobigtoship): ?>
                                    <div class="pull-right" style="margin-right: 24px;">
                                        <i title="Oversized/Freight Item " class="fas fa-1x fa-truck"></i>
                                        <span></span>
                                    </div>

                                <?php endif; ?>
                                <div class="favorite">

                                    <a href="#" class="modal-click category-favorite btn btn-icon-fav"
                                       data-url="<?= StoreUrl::to('favorites/category-modal'); ?>"
                                       data-sku="<?= $sku ?>"
                                       data-toggle="modal" data-target="#favoritesModal">
                                        <i class="material-icons">
                                            favorite
                                        </i>
                                    </a>

                                    <!-- <a href="#" data-toggle="modal" data-target="#favoritesModal">
                                         <i class="material-icons">favorite</i>
                                     </a>-->
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="info-meta--bottom">
                            <?php if (!Yii::$app->user->isGuest && !$outofstock): ?>
                                <div class="product-price">
                                    <?php if ($product->type != CatalogProduct::GROUPED && !$getQuote): ?>
                                        <div id="itemPrice" class="price"
                                             data-price="<?= str_replace(',', '', CatalogProduct::getPriceString($product_id, false)); ?>">
                                            <?= CatalogProduct::getPriceHtml($product_id, false, true) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-sku">
                                <div class="sku">SKU:
                                    <?= $sku ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-description pad-top-sm pad-btm-sm">
                        <?= CatalogProduct::getAttributeValue($product_id, 'short-description'); ?>
                    </div>
                    <?php if (!$outofstock): ?>
                    <div class="product-actions">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <div class="product-actions--guest">
                                <a href="<?= StoreUrl::to('account/login?redirect=' . $url) ?>" class="btn btn-default">
                                    LOGIN
                                </a> or <a href="<?= StoreUrl::to('account/register') ?>" class="btn btn-primary">
                                    CREATE ACCOUNT
                                </a> to view pricing.
                            </div>
                            <?php if (CatalogProduct::getAttributeValue($product_id, 'redirect-to-brd')): ?>
                                <div class="panel pad-xs">
                                    <div class="panel-bdy">
                                        <h4 class="pad-btm-sm">Not a healthcare professional?</h4>
                                        <a href="https://www.bodyreliefdepot.com/brd/<?= CatalogProduct::getProductSlug($product_id) ?>.html"
                                           target="_blank" class="btn btn-alt">Buy on Body Relief Depot</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                        <div class="product-actions--customer">
                            <form id="product-form" data-action="<?= StoreUrl::to('cart/process'); ?>">
                                <?php if ($product->type == CatalogProduct::GROUPED): ?>
                                    <?= "grouped" ?>
                                    <?= $this->render('view/_grouped', ['product' => $product, 'getQuote' => $getQuote]) ?>
                                <?php endif; ?>
                                <?php if (CatalogProduct::getTieredPricing($product_id)): ?>
                                    <?= "tiered" ?>
                                    <?= $this->render('view/_tiered', ['product_id' => $product_id]); ?>
                                <?php endif; ?>
                                <?php if (CatalogProduct::hasOptions($product_id)): ?>
                                    <?= "options" ?>
                                    <?= $this->render('view/_options', ['product_id' => $product_id, 'sku' => $sku]) ?>
                                <?php else: ?>

                                    <?php if ($product->type != CatalogProduct::GROUPED): ?>
                                        <div class="form-group quantity m-b--sm">
                                            <label class="control-label">Quantity</label>
                                            <input id="quantity" class="text-center form-control" type="text"
                                                   data-sku="<?= CatalogProduct::getSku($product_id) ?>"
                                                   data-pid="<?= $product_id ?>" name="qty[]" value="1">
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($getQuote): ?>
                                    <a id="get-quote" class="modal-click btn btn-primary btn-xl btn-block btn-pad-btm"
                                       href="#"
                                       data-url="<?= StoreUrl::to('quote/modal'); ?>" data-target="#getQuoteModal"
                                       data-sku="<?= $sku ?>"
                                       data-pid="<?= $product_id ?>">
                                        Get Quote</a>
                                <?php else: ?>
                                    <a id="addCart" class="cart-add btn btn-primary btn-xl btn-block btn-pad-btm"
                                       data-sku="<?= $sku ?>" data-pid="<?= $product_id ?>">Add to Cart</a>
                                <?php endif; ?>

                                <?php endif; ?>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bd-xy">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs--product text-center" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab"
                                                              data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#additionalInfo" aria-controls="additionalInfo" role="tab"
                                               data-toggle="tab">Additional Information</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="bd-b pad-xs product-details">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="details">
                        <div class="row">
                            <div class="pad-btm-xs col-sm-4 col-sm-4 col-lg-4">
                                <h4>Details</h4>
                            </div>
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <?= CatalogProduct::getAttributeValue($product_id, 'description'); ?>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="additionalInfo">
                        <div class="row">
                            <div class="col-sm-4 col-sm-4 col-lg-4">
                                <h4>Additional Info</h4>
                            </div>
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <?php if ($attachments): ?>
                                    <ul class="additional-info">
                                        <?php foreach ($attachments as $attachment): ?>
                                            <li>
                                                <i class="material-icons">
                                                    insert_drive_file
                                                </i>
                                                <a target="_blank"
                                                   href="/uploads/<?= $attachment->file_name ?>"><?= $attachment->title ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= Yii::$app->controller->renderPartial('/products/view/related', ['product_id' => $product_id]); ?>
<?= Yii::$app->controller->renderPartial('/products/list/_detail_banner'); ?>

<div class="modal modal-xs fade" id="favoritesModal" tabindex="-1" role="dialog"></div>
<?php if ($getQuote): ?>
    <div class="modal modal-xs fade" id="getQuoteModal" tabindex="-1" role="dialog"></div>
<?php endif; ?>

