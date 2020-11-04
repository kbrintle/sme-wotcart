<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\catalog\CatalogProduct;
use common\models\settings\SettingsStore;
use common\models\catalog\CatalogAttributeOption;
use common\components\CurrentStore;
use common\models\store\StoreBanner;
use common\models\store\StoreEvent;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogProductGallery;

$leftShop = StoreBanner::getBannerByPageLocation("leftshop", CurrentStore::getStoreId());
$rightShop = StoreBanner::getBannerByPageLocation("rightshop", CurrentStore::getStoreId());
$bigShop = StoreBanner::getBannerByPageLocation("bigshop", CurrentStore::getStoreId());

// Get featured products
$settings = SettingsStore::find()->one();
$catalogProduct = new CatalogProduct();
$featured = $catalogProduct->getStoreFeaturedProducts();

$categories = CatalogCategory::getHomePageCategories(12);
?>
<div class="site-index">
    <!-- Render Hero Slider -->
    <?= $this->render('partials/_hero_slider.php', []) ?>

    <?php if ($promo_images): ?>
        <section class="">
            <div class="home-promos">
                <ul class="home-promos-list hidden-xs">
                    <?php foreach ($promo_images as $promo_image): ?>
                        <li class="home-promos-list-item">
                            <?php if ($promo_image->link): ?>
                            <?php if (strpos($promo_image->link, 'http') !== false): ?>
                            <a href="<?= StoreUrl::to($promo_image->link); ?>" class="">
                                <?php else: ?>
                                <a href="<?= StoreUrl::to($promo_image->link); ?>" class="">
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <img src="<?= Assets::mediaResource($promo_image->image); ?>"
                                         class="img-responsive"/>
                                    <?php if ($promo_image->link): ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="home-promos-list visible-xs">
                    <div id="carousel-home-promos" class="carousel carousel-home-promos slide" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <?php foreach ($promo_images as $index => $promo_image): ?>
                                <?php if ($index == 0): ?>
                                    <div class="item active">
                                        <div class="home-promos-list-item">
                                            <?php if ($promo_image->link): ?>
                                            <a href="<?= StoreUrl::to($promo_image->link); ?>" class="">
                                                <?php endif; ?>
                                                <img src="<?= Assets::mediaResource($promo_image->image); ?>"
                                                     class="img-responsive"/>
                                                <?php if ($promo_image->link): ?>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="item">
                                        <div class="home-promos-list-item">
                                            <?php if ($promo_image->link): ?>
                                            <a href="<?= StoreUrl::to($promo_image->link); ?>" class="">
                                                <?php endif; ?>
                                                <img src="<?= Assets::mediaResource($promo_image->image); ?>"
                                                     class="img-responsive"/>
                                                <?php if ($promo_image->link): ?>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach; ?>
                        </div>
                        <!-- Indicators -->
                        <ol class="carousel-indicators ">
                            <li data-target="#carousel-home-promos" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-home-promos" data-slide-to="1" class="active"></li>
                            <li data-target="#carousel-home-promos" data-slide-to="2" class="active"></li>
                            <li data-target="#carousel-home-promos" data-slide-to="3" class="active"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="pad-sm">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="banner-marketing-container" style="background-image: url('<?= $leftShop->image ?>');">
                        <div class="banner-marketing">
                            <h5><?= $leftShop->title ?></h5>
                            <h2 class="text-white"><?= $leftShop->sub_title ?></h2>
                            <a class="btn btn-secondary"
                               href="<?= StoreUrl::to($leftShop->button_url) ?>"><?= $leftShop->button_text ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="banner-marketing-container" style="background-image: url('<?= $rightShop->image ?>');">
                        <div class="banner-marketing">
                            <h5><?= $rightShop->title ?></h5>
                            <h2 class="text-white"><?= $rightShop->sub_title ?></h2>
                            <a class="btn btn-secondary"
                               href="<?= StoreUrl::to($rightShop->button_url) ?>"><?= $rightShop->button_text ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($featured): ?>
        <section class="">
            <div class="container">
                <div class="row pad-sm">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <h3 class="pad-btm">Featured Products</h3>
                    </div>
                    <?php foreach ($featured as $feature): ?>
                        <div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-6">
                            <div class="product-thumb">
                                <div class="image-div pad-xs">
                                    <?php if (CatalogProduct::getWebOnly($feature->id)): ?>
                                        <span class="label label-web">Web Only</span>
                                    <?php endif; ?>
                                    <?php if (CatalogProduct::isOnSale($feature->id)): ?>
                                        <span class="label label-sale">Sale</span>
                                    <?php endif; ?>
                                    <a href="<?= StoreUrl::to('shop/products/' . $feature->slug); ?>">
                                        <?php
                                        $base_image = CatalogProductGallery::getImages($feature->id);
                                        $image = Assets::productResource($base_image);
                                        ?>
                                        <img src="<?= $image ?>" class="img-responsive img-responsive-mobile"/>
                                    </a>
                                </div>
                                <div class="thumb-footer">
                                    <h5>
                                        <a href="<?= StoreUrl::to('shop/products/' . $feature->slug); ?>">
                                            <?= CatalogProduct::getBrand($feature->id); ?><br/>
                                            <?= CatalogProduct::getName($feature->id); ?><br/>
                                            <?php
                                            $catalog_value = CatalogAttributeOption::findOne(
                                                CatalogProduct::getAttributeValue($feature->id, 'mattress-comfort-level')
                                            );
                                            ?>
                                            <?= $catalog_value ? $catalog_value->value : ''; ?>
                                        </a>
                                    </h5>
                                </div>
                                <?php if (!Yii::$app->user->isGuest && $feature->type !== "grouped"): ?>
                                    <p class="price">$<?= CatalogProduct::getPriceString($feature->id, false); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="visible-xs">
                        <div id="carousel-featured-products" class="carousel carousel-products slide"
                             data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner" role="listbox">
                                <?php foreach ($featured

                                as $index => $feature): ?>
                                <?php if ($index == 0): ?>
                                <div class="item active">
                                    <?php else: ?>
                                    <div class="item">
                                        <?php endif; ?>
                                        <div class="product-layout col-lg-4 col-md-4 col-sm-6">
                                            <div class="product-thumb transition product__ui">
                                                <?php if (CatalogProduct::getWebOnly($feature->id)): ?>
                                                    <span class="label label-web">Web Only</span>
                                                <?php endif; ?>
                                                <div class="image pad-xs">
                                                    <a href="<?= StoreUrl::to('shop/products/' . $feature->slug); ?>">
                                                        <?php
                                                        $image = CatalogProduct::getAttributeValue($feature->id, 'base-image');
                                                        $image = $image ? Assets::productResource($image) : Assets::themeResource('products/no-image.png');
                                                        ?>
                                                        <img src="<?= $image ?>"
                                                             class="img-responsive img-responsive-mobile"/>
                                                    </a>
                                                </div>
                                                <div class="caption">
                                                    <h4 class="text-center">
                                                        <a href="<?= StoreUrl::to('shop/products/' . $feature->slug); ?>">
                                                            <?= CatalogProduct::getBrand($feature->id); ?><br/>
                                                            <?= CatalogProduct::getName($feature->id); ?><br/>

                                                            <?= $catalog_value ? $catalog_value->value : ''; ?>
                                                        </a>
                                                    </h4>
                                                    <p class="price text-center"><?= CatalogProduct::getPriceString($feature->id, true); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Indicators -->
                                <ol class="carousel-indicators ">
                                    <li data-target="#carousel-featured-products" data-slide-to="0" class="active"></li>
                                    <li data-target="#carousel-featured-products" data-slide-to="1" class="active"></li>
                                    <li data-target="#carousel-featured-products" data-slide-to="2" class="active"></li>
                                </ol>
                            </div>
                        </div>
                        <!--                        <div class="col-sm-12">-->
                        <!--                            --><?php //echo Html::a("Shop Products", StoreUrl::to('shop'), ['class' => 'btn btn-default btn-responsive']); ?>
                        <!--                        </div>-->
                    </div>
                </div>
        </section>
    <?php endif; ?>

    <section class="pad-btm">
        <div class="container">
            <div class="banner-marketing-container" style="background-image: url('<?= $bigShop->image ?>');">
                <div class="banner-marketing">
                    <h5><?= $bigShop->title ?></h5>
                    <h2 class="text-white"><?= $bigShop->sub_title ?></h2>
                    <a class="btn btn-secondary"
                       href="<?= StoreUrl::to($bigShop->button_url) ?>"><?= $bigShop->button_text ?></a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="events">
                        <div class="clearfix">
                            <h3 class="pull-left">Upcoming Events</h3>
                            <a href="events" class="btn btn-link pull-right">View all</a>
                        </div>
                        <ul class="events">
                            <?php foreach ($events as $event): ?>
                                <li class="events-event">
                                    <div class="events-event--icon">
                                        <i class="material-icons">
                                            event
                                        </i>
                                    </div>
                                    <div class="events-event--content">
                                        <h3 class="title"><?= $event->title ?></h3>
                                        <div class="events-event--meta">
                                            <span class="date"><?= StoreEvent::getEventDateHtml($event->id) ?></span>
                                            <a href="<?= StoreUrl::to('events/' . $event->slug) ?>"
                                               class="btn btn-link text-left">View event</a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-5 col-sm-offset-1 col-md-5 col-md-offset-1 col-lg-5 col-lg-offset-1">
                    <div class="tw-feed">
                        <h3>Twitter Feed</h3>
                        <div class="feed">
                            <a class="twitter-timeline" href="https://twitter.com/SMEIncUSA"
                               data-widget-id="612006736929665024">Tweets by @SMEIncUSA</a>
                            <script>!function (d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0],
                                        p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + "://platform.twitter.com/widgets.js";
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, "script", "twitter-wjs");</script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="container-fluid">
            <div class="row pad-lg">
                <div class="categories">
                    <h2 class="auto-margin">SHOP BY CATEGORY</h2>
                    <?php foreach ($categories as $category): ?>
                        <div class="col-sm-2 col-md-2 col-lg-2">
                            <div class="card card-cat">
                                <div class="card-img"
                                     style="background-image: url('<?= Assets::mediaResource($category->thumbnail) ?>')">


                                </div>
                                <div class="card-body">
                                    <a href="<?= StoreUrl::to('shop/category/' . $category->slug) ?>" class="card-link">
                                        <?= $category->name ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-lightestgray">
        <div class="container">
            <div class="row pad-lg sme-org">
                <h2 class="auto-margin">SME is proud to be part of these organizations</h2>
                <?= Html::a(Html::img(Assets::themeResource('sme/sme-org.svg'), ['alt' => Yii::$app->name, 'class' => 'auto-margin org-logo-img img-responsive']), Yii::$app->homeUrl); ?>
            </div>
        </div>
</div>
</section>

</div>
