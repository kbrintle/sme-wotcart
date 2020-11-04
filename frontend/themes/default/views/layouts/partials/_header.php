<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\catalog\CatalogCategory;
use common\models\sales\SalesQuote;
use common\models\core\CoreConfig;
use common\components\CurrentStore;
use common\models\store\StoreLocation;

AppAsset::register($this);
$store = CurrentStore::getStore();
$locations = StoreLocation::find()
    ->store()
    ->orderBy('id')
    ->all();
$settings = CurrentStore::getSettings();
?>

<header class="header">
    <div class="container-fluid">
        <div class="row header-banner">
            <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="header--banner-promo">
                    <a href="<?= StoreUrl::to(CoreConfig::getStoreConfig('design/nav/banner_url')) ?>">
                        <span class="label label-banner"><?= strtoupper(CoreConfig::getStoreConfig('design/nav/banner_type')) ?></span>
                        <span class="promo"><?= CoreConfig::getStoreConfig('design/nav/banner_text') ?></span>
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 hidden-md hidden-sm hidden-xs">
                <div class="header--banner-options clearfix row">
                    <div class="search col-sm-4 col-md-4 col-lg-4 col-md-offset-5 hidden-sm hidden-xs">
                        <form action="/search" data-action="/search" method="get">
                            <div class="input-group">
                                <input id="search-input" type="search" name="q" class="form-control"
                                       placeholder="Search <?= $store->name ?>"/>
                                <button type="submit" id="search"><i class="material-icons">search</i></button>
                            </div>
                        </form>

                        <script type="text/javascript">
                            Searchanise = {};
                            Searchanise.host = 'http://www.searchanise.com';
                            Searchanise.api_key = '<?= $store->searchanise_api_key ?>';
                            Searchanise.SearchInput = '#search-input';
                            Searchanise.options = {};
                            Searchanise.options.ResultsFallbackUrl = '/sme/search/?q=';
                            Searchanise.options.PriceFormat = {
                                decimals_separator: '.',
                                thousands_separator: ',',
                                symbol: '$',

                                decimals: '2',
                                rate: '1',
                                after: false
                            };

                            (function () {
                                var __se = document.createElement('script');
                                __se.src = 'https://www.searchanise.com/widgets/v1.0/init.js';
                                __se.setAttribute('async', 'true');
                                var s = document.getElementsByTagName('script')[0];
                                s.parentNode.insertBefore(__se, s);
                            })();
                            //]]>
                        </script>
                    </div>

                    <ul class="nav-account col-md-2 col-md-offset-1 text-right">
                        <li class="col-md-4">
                            <a href="<?= StoreUrl::to('reward-rules') ?>">
                                <i class="far fa-gift fa-2x"></i>
                            </a>
                        </li>
                        <li class="col-md-4">
                            <a href="<?= StoreUrl::to('favorites/list') ?>">
                                <i class="material-icons fav">favorite_border</i>
                            </a>
                        <li class="col-md-4">
                            <a href="<?= StoreUrl::to('account') ?>">
                                <i class="material-icons person">person</i>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="row header-bottom">
            <?php
            NavBar::begin([
                'brandLabel' => Html::img(Assets::mediaResource(CoreConfig::getStoreConfig('general/design/logo')), ['class' => 'logo__img img-responsive', 'alt' => Yii::$app->name]),
                'brandUrl' => StoreUrl::homeurl(),
                'innerContainerOptions' => [
                    'class' => 'inner'
                ],
            ]);

            $productCategories = CatalogCategory::find()
                ->where(['is_active' => true, 'is_deleted' => false, 'is_brand' => false, 'is_nav' => true])
                ->andWhere(['parent_id' => null])
                ->andWhere(['or',
                    ['store_id' => 0],
                    ['store_id' => \common\components\CurrentStore::getStoreId()]
                ])
                ->all();

            $brands = CatalogCategory::find()
                ->where(['is_active' => true, 'is_deleted' => false, 'is_brand' => true, 'is_nav' => true])
                ->andWhere(['or',
                    ['store_id' => 0],
                    ['store_id' => \common\components\CurrentStore::getStoreId()]
                ])
                ->orderBy(['name' => SORT_ASC])
                ->all();
            if ($brands) {
                foreach ($brands as $brand) {
                    $brandCategories[] = [
                        'label' => $brand->name,
                        'url' => StoreUrl::to("shop/category/$brand->slug"),
                        'options' => ['class' => 'cat-parent'],
                        'linkOptions' => ['class' => 'cat-link cat--link-parent']
                    ];
                }
            }

            $count = SalesQuote::getItemsQty();
            $itemCount = "Cart";
            if ($count > 0 && !Yii::$app->user->isGuest) {
                $itemCount .= " ($count)";
            }
            $specials = ['label' => 'Specials', 'url' => StoreUrl::to('specials')];

            if ($productCategories) {
                foreach ($productCategories as $productCategory) {
                    $subItems = [];
                    $subCategories = CatalogCategory::getProductNavChildCategories($productCategory->id);
                    foreach ($subCategories as $subCategory) {
                        $subItems[] = [
                            'label' => $subCategory->name,
                            'url' => StoreUrl::to("shop/category/$subCategory->slug"),
                            'options' => ['class' => 'cat--parent-sub'],
                            'linkOptions' => ['class' => 'cat-link cat--link-sub']
                        ];
                    }

                    $shopCategories[] = [
                        'label' => $productCategory->name,
                        'url' => StoreUrl::to("shop/category/$productCategory->slug"),
                        'items' => $subItems,
                        'options' => ['class' => 'cat-parent'],
                        'linkOptions' => ['class' => 'cat-link cat--link-parent']
                    ];
                }

                $menuItems = [
                    ['label' => '<form class="hidden-lg" action="/search" data-action="/search" method="get">
                                <input type="search" name="q" class="form-control"
                                       placeholder="Search ' . $store->name . '"/>
                                <button style="background: none; border: 0; margin-top:-33px; float:right;" type="submit" id="search"><i class="material-icons">search</i></button>
                        </form>'],
                    ['label' => 'Products', 'items' => $shopCategories, 'options' => ['class' => 'dropdown-xl'], 'dropDownCaret' => ""],
                    ['label' => 'Brands', 'items' => $brandCategories, 'options' => ['class' => 'dropdown-xl'], 'dropDownCaret' => ""],
                    ['label' => 'New', 'url' => StoreUrl::to('shop/category/new')],
                    ['label' => 'Sale', 'url' => StoreUrl::to('shop/category/sale')],
                    ['label' => 'About', 'url' => StoreUrl::to('about-us')],
                    ['label' => 'Events', 'url' => StoreUrl::to('events')],
                    ['label' => 'Contact', 'url' => StoreUrl::to('contact')],
                    ['label' => "Account", 'url' => StoreUrl::to('account'), 'options' => ['class' => 'hidden-lg']],
                    ['label' => "Favorites", 'url' => StoreUrl::to('favorites/list'), 'options' => ['class' => 'hidden-lg']],
                    ['label' => "$itemCount", 'url' => StoreUrl::to('cart'), 'options' => ['class' => 'hidden-lg']],
                ];

                $menuCart = [
                    ['label' => 'Pay Bill', 'url' => StoreUrl::to('pay-bill'), 'options' => ['target' => '_blank', 'class' => 'navbar--nav-bill hidden-md hidden-sm hidden-xs'], 'linkOptions' => ['class' => 'bill']],
                    ['label' => "$itemCount", 'url' => StoreUrl::to('cart'), 'options' => ['class' => 'navbar--nav-cart hidden-md hidden-sm hidden-xs'], 'linkOptions' => ['class' => 'cart', 'id' => 'cartBadge']],
                    ['label' => "Account", 'url' => StoreUrl::to('account'), 'options' => ['class' => 'navbar--nav-cart hidden-lg hidden-md hidden-sm hidden-xs'], 'linkOptions' => ['class' => 'cart', 'id' => 'cartBadge']],

                ];
            }
            echo Nav::widget(
                ['encodeLabels' => false,
                    'options' => ['class' => 'navbar-nav'],
                    'items' => $menuItems,
                ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuCart
            ]);

            echo '';

            NavBar::end();
            ?>
        </div>
    </div>
</header>