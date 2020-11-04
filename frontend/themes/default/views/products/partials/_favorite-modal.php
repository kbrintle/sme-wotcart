<?php

use yii\helpers\ArrayHelper;
use common\models\store\StoreFavoriteList;
use app\components\StoreUrl;
use yii\helpers\Html;

?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form data-action="<?= StoreUrl::to('favorites/add')?>">
            <div class="modal-header">
                <h5 class="modal-title">Choose the folder would you like to add this product to:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= Html::dropDownList('folder', '', ArrayHelper::map(StoreFavoriteList::getCustomerFolders(), 'list_id', 'title'), ["id" => "Folders", 'class'=>'form-control']); ?>
                <?= Html::hiddenInput('product_id', $id); ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary submit-favorites" data-sku="<?= $sku ?>" data-pid="<?= $id ?>">Add to Favorites</a>
            </div>
        </form>
    </div>
</div>