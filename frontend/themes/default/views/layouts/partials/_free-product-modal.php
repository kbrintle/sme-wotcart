<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use common\models\catalog\CatalogProduct;
use common\models\store\StoreFavoriteList;
use frontend\components\Assets;
use common\models\catalog\CatalogProductAttachment;
use common\models\catalog\CatalogProductGallery;

?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header text-center">
            <h4 class="modal-title" id="emailStoreLabel">Select Your Free Gift</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-12 pad-btm">
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                $base_image = CatalogProductGallery::getImages($product->id);
                                $image = Assets::productResource($base_image);
                                ?>
                                <img src="<?= $image ?>"
                                     class="zoom center-block img-responsive img-responsive-mobile"/>
                            </div>
                            <div class="col-md-4">
                                <h3><?= CatalogProduct::getName($product->id) ?></h3>
                            </div>
                            <div class="col-md-4 text-center">
                                <?= Html::button('Select', ['class' => 'gift-choose btn btn-primary', 'data-action' => StoreUrl::to('cart/promocode'), 'data-pid' => $product->id]); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
