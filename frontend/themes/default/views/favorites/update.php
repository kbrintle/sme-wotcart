<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use \common\models\catalog\CatalogProduct;
use common\components\CurrentStore;
use frontend\components\Assets;
use yii\helpers\ArrayHelper;
use common\models\store\StoreFavoriteList;

$store = CurrentStore::getStore();
$this->title = $list->title;
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ["/$store->url/shop"]];
$this->params['breadcrumbs'][] = ['label' => 'My Favorites', 'url' => ["/$store->url/favorites/list"]];
$this->params['breadcrumbs'][] = $list->title;
?>

<style>

    tbody tr {
        cursor: move;
    }

</style>

<div class="page--content-favorites account page--content-pad">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <div class="sidebar">
                    <?php echo $this->render('../account/_nav.php') ?>
                </div>
            </div>
            <div class="col-sm-9 col-md-9 col-lg-9">
                <section class="bg-lightgray margin-top-30">
                    <div>
                        <div id="favorite-update" class="panel">
                            <?php if (empty($items)): ?>
                                <br>
                                <p>"<?= $list->title ?>" is empty.</p>
                            <?php else: ?>
                                <?= Html::beginForm([StoreUrl::to('favorites/action/' . $list_id)], 'post'); ?>
                                <button class="btn btn-primary pull-right right-small-margin" type="submit"
                                        name="action"
                                        value="update">Update
                                </button>
                                <table class="favorite-list table table-striped">
                                    <thead class="auto-margin">
                                    <tr>
                                        <td></td>
                                        <td>Product</td>
                                        <td>Details</td>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php $product = CatalogProduct::getProduct($item['product_id']); ?>
                                        <tr data-pid="<?= $item['product_id'] ?>">
                                            <td>
                                                <?= Html::checkbox('cb[' . $item['sku'] . ']', false, ['class' => '']) ?>
                                            </td>
                                            <td>
                                                <?php
                                                $id = $product->id;
                                                if (isset($product->parent_id)) {
                                                    $id = $product->parent_id;
                                                }
                                                $base_image = CatalogProduct::getGalleryImages($id, 'base-image');
                                                $image = $base_image ? Assets::productResource($base_image->value) : Assets::mediaResource('');
                                                ?>
                                                <img src="<?= $image ?>"/>
                                            </td>
                                            <td class="text-left">
                                                <?php
                                                $name = CatalogProduct::getAttributeValue($product->id, 'name');
                                                echo Html::a($name, StoreUrl::to("/shop/products/$product->slug")); ?>
                                                <?php if ($item['options']): ?>
                                                    <?php foreach ($item['options'] as $option): ?>
                                                        <br>
                                                        <?= $option; ?>
                                                    <?php endforeach; ?>
                                                    <br>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <input name="qty[<?= $item['sku'] ?>]"
                                                       class="text-center form-control" value="<?= $item['qty'] ?>"
                                            </td>
                                            <td>
                                                $<?= money_format($item['itemsPrice'], 2) ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                   href="<?php echo StoreUrl::to('favorites/remove-item/' . $item['item_id']) ?>">Remove</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                            <a href="#" class="btn btn-primary" data-target="#nameChangeModal" data-toggle="modal">
                                Change List Name
                            </a>
                            <a href="#" class="btn btn-danger left-small-margin" data-target="#deleteModal"
                               data-toggle="modal">
                                Delete List
                            </a>
                            <?php if (!empty($items)): ?>
                                <button class="btn btn-default pull-right" type="submit" name="action"
                                        value="addtocart">
                                    Add to Cart
                                </button>
                                <?php if ($lists = ArrayHelper::map(StoreFavoriteList::getCustomerFolders(), 'list_id', 'title')): ?>
                                    <?php
                                    unset($lists[(int)$list_id]); ?>
                                    <?php if (sizeof($lists) > 0): ?>
                                        <a href="#" class="btn btn-primary pull-right right-small-margin"
                                           data-target="#moveToModal" data-toggle="modal">
                                            Move to...
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?= Html::endForm() ?>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>
            </section>
        </div>
    </div>
</div>

<!-- Change List Name Modal -->
<div class="modal modal__ui fade" id="nameChangeModal" tabindex="-1" role="dialog" aria-labelledby="createModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change List Name</h5>
            </div>
            <div class="modal-body clearfix">
                <?= Html::beginForm([StoreUrl::to('favorites/action/' . $list_id)], 'post'); ?>
                <div class="form-group">
                    <?= Html::input('text', 'list-name', $list->title, ['class' => 'form-control']) ?>
                </div>
                <button class="btn btn-primary pull-right right-small-margin" type="submit"
                        name="action"
                        value="name-change">Save
                </button>
                <?= Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal modal__ui fade" id="moveToModal" tabindex="-1" role="dialog" aria-labelledby="moveToModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form data-action="<?= StoreUrl::to('favorites/action') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Choose the folder would you like to add this product to:</h5>
                </div>
                <div class="modal-body">
                    <?php if (isset($lists)): ?>
                       <!--<?/*= Html::hiddenInput('move_to_list', $id); */?>-->
                        <?= Html::dropDownList('folder', '', $lists, ["id" => "lists", 'class' => 'form-control']); ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary submit-move-favorites">Move</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete List Modal -->
<div class="modal modal__ui fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="createModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you wish to delete "<?= $list->title ?>
                    "?</h5>
            </div>
            <div class="modal-body clearfix text-center">
                <?= Html::beginForm([StoreUrl::to('favorites/action/' . $list_id)], 'post'); ?>
                <button class="btn btn-primary right-small-margin" data-dismiss="modal" name="action">No</button>
                <button class="btn btn-danger left-small-margin" type="submit" name="action" value="delete">
                    Yes
                </button>
                <?= Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>





