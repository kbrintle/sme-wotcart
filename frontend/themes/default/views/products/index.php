<?php
/* @var $this yii\web\View */

use  common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogProduct;
use app\components\StoreUrl;
use common\components\CurrentStore;
$category = CatalogCategory::getCategory($category);
if (!isset($category)) {
    throw new \yii\web\NotFoundHttpException();
}
$crumbs = CatalogProduct::getCategoryBreadcrumbs($category);

$this->title = $category->name;
if ($crumbs) {
    foreach ($crumbs as $crumb) {
        $this->params['breadcrumbs'][] = ['label' => $crumb['name'], 'url' => [\app\components\StoreUrl::to('/shop/category/' . $crumb['url'])]];
    }
    if ($category->parent_id !== null) {
        $this->params['breadcrumbs'][] = $this->title;
    }
}

?>
<section class=""
         ng-controller="ProductGridController"
         ng-cloak>


    <?= Yii::$app->controller->renderPartial('//products/list/_category_header', ['category' => $category->slug]); ?>

    <div class="container">
        <div class="loading_pane"
             ng-show="loading">
            <div class="row">
                <div class="col-xs-12 text-center">

                    <div class="gutter">
                        <div class="loader">
                            <div class="sk-cube-grid">
                                <div class="sk-cube sk-cube1"></div>
                                <div class="sk-cube sk-cube2"></div>
                                <div class="sk-cube sk-cube3"></div>
                            </div>
                        </div>

                        <span>Loading Products</span>
                    </div>

                </div>
            </div>
        </div>


        <div class="row"
             ng-show="!loading">

            <div class="col-md-3 hidden-sm" id="column-left">
                <div class="sidebar">
                    <?php if (!Yii::$app->mobileDetect->isMobile()): ?>
                        <?= Yii::$app->controller->renderPartial('//products/list/_category_sidebar', [
                            'hidden_filters' => isset($hidden_filters) ? $hidden_filters : null]); ?>
                    <?php endif; ?>
                </div>
                <div class="marketing-banner">
                    <?= Yii::$app->controller->renderPartial('/products/list/_category_banner', []); ?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pad-top">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <p class="sm">
                            <?php echo CatalogCategory::getDescription($category->slug) ?>
                        </p>
                    </div>
                </div>
                <?php if (Yii::$app->mobileDetect->isMobile()): ?>
                    <div class="mobile_filters">
                        <div class="mobile_filters-trigger">
                            <a href="#collapse_mobile-filters"
                               data-toggle="collapse"
                               aria-expanded="false"
                               aria-controls="collapse_mobile-filters">
                                <span>Filter & Sort <i class="material-icons">filter_list</i></span>
                            </a>
                        </div>
                        <div class="mobile_filters-container collapse"
                             id="collapse_mobile-filters">
                            <?php //$this->render('list/_sort'); ?>

                            <?= Yii::$app->controller->renderPartial('//products/list/_category_sidebar', [
                                'hidden_filters' => isset($hidden_filters) ? $hidden_filters : null]); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="visible-sm">
                    <div class="mobile_filters">
                        <div class="mobile_filters-trigger">
                            <a href="#collapse_mobile-filters"
                               data-toggle="collapse"
                               aria-expanded="false"
                               aria-controls="collapse_mobile-filters">
                                <span>Filter & Sort <i class="material-icons">filter_list</i></span>
                            </a>
                        </div>
                        <div class="mobile_filters-container collapse"
                             id="collapse_mobile-filters">
                            <?php //$this->render('list/_sort'); ?>

                            <?= Yii::$app->controller->renderPartial('//products/list/_category_sidebar', [
                                'hidden_filters' => isset($hidden_filters) ? $hidden_filters : null
                            ]); ?>
                        </div>
                    </div>
                </div>

                <?php if (!Yii::$app->mobileDetect->isMobile()): ?>
                    <?= $this->render('list/_sort'); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-6"
                         ng-repeat="product in filtered_products"
                         ng-show="filtered_products.length > 0"
                         data-id = "{{product.id}}">
                        <div class="product-thumb">
                            <div class="image-div">
                      <!--          <a ng-if="product.attributes['favorite'].value == 1" href="#"
                                   class="modal-click category-favorite btn btn-icon-fav" data-url="<?/*=StoreUrl::to('favorites/category-modal');*/?>" data-sku="{{product.attributes['sku'].value}}"
                                   data-toggle="modal" data-target="#favoritesModal">
                                    <i class="material-icons">
                                        favorite
                                    </i>
                                </a>-->
                                <span ng-if="product.attributes['on_sale'].value == 1" class="label label-sale">Sale</span>
                                <span ng-if="product.attributes['new'].value == 1 && product.attributes['sale'].value == 0 " class="label label-new">New</span>
                                <span ng-if="product.attributes['new'].value == 1 && product.attributes['sale'].value == 1 " class="label label-new-sale">New</span>
                                <a href="{{product.url}}">
                                    <img src="{{product.attributes['base-image'].value}}"
                                         class='center-block img-responsive' alt="{{product.attributes.name.value}}"/>
                                </a>
                            </div>
                            </a>
                            <div class="thumb-footer">
                                <h5>
                                    <a href="{{product.url}}">
                                        {{product.attributes.name.value}}
                                    </a>
                                </h5>
                            </div>
                            <div class="product-actions clearfix">
                                <?php if (!Yii::$app->user->isGuest): ?>
                                <form id="product-form" data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                                    <div ng-if="product.type != 'grouped' && product.attributes['out-of-stock'].value != 1 " class="pull-left">
                                        <span class="price"
                                              ng-if="product.attributes.price.value ">
                                            <span ng-if="product.attributes['special-price'].value >= product.attributes['price'].value"
                                                  class="product-price">{{product.attributes.price.value | currency}}</span>
                                            <del ng-if="product.attributes['special-price'].value < product.attributes['price'].value"
                                                 class="product-price ">{{product.attributes.price.value | currency}}</del>
                                        </span>
                                        <span class="price sale-price"
                                              ng-if="product.attributes['special-price'].value < product.attributes['price'].value">
                                            <span class="product-price">{{product.attributes['special-price'].value | currency}}</span>
                                        </span>
                                    </div>
                                    <div ng-if="product.attributes['out-of-stock'].value != 1">
                                        <a id="addCart" ng-if="product.attributes['cart'].value == 1 && (product.attributes['get-quote'].value == undefined || product.attributes['get-quote'].value == 0)"
                                           class="cart-add btn btn-icon-cart pull-right"
                                           data-sku="{{product.attributes['sku'].value}}"
                                           data-pid="{{product.id}}">
                                            <i class="material-icons">add_shopping_cart</i>
                                        </a>
                                        <a class="modal-click btn btn-icon-cart pull-right"
                                           ng-if="product.attributes['get-quote'].value == 1"
                                           href="#"
                                           data-url="<?=StoreUrl::to('quote/modal');?>"
                                           data-toggle="modal" data-target="#getQuoteModal"
                                           data-sku="{{product.attributes['sku'].value}}" data-pid="{{product.id}}">
                                            Get Quote</a>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="loading_pane" ng-show="filtered_products.length == 0">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <div class="gutter">
                                <span>No Products found</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"
                     ng-if="filtered_products.length > 0">
                    <div class="col-xs-12">
                        <nav class="pagination-nav">
                            <ul class="pagination">
                                <li ng-class="{'disabled':min_page()}">
                                    <a aria-label="Previous"
                                       ng-click="page_back()"
                                       ng-disabled="min_page()"
                                       class="pagination-top" >
                                        <span aria-hidden="true"><i class="material-icons">chevron_left</i></span>
                                    </a>
                                </li>
                                <li ng-class="{'active':page==current_page}"
                                    ng-repeat="page in pages()">
                                    <a class="pagination-top" ng-click="go_to_page(page)">{{page}}</a>
                                </li>
                                <li ng-class="{'disabled':max_page()}">
                                    <a aria-label="Next"
                                       ng-click="page_forward()"
                                       ng-disabled="max_page()"
                                       class="pagination-top" >
                                        <span aria-hidden="true"><i class="material-icons">chevron_right</i></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<div class="modal modal-xs fade" id="favoritesModal" tabindex="-1" role="dialog"></div>
<div class="modal modal-xs fade" id="getQuoteModal" tabindex="-1" role="dialog"></div>


