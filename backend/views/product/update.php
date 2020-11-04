<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\catalog\CatalogAttributeValue;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\Product */

$this->title = 'Update Product: ' . CatalogAttributeValue::storeValue('name', $model->id);
?>

<style>
    .modal.fade .modal-dialog {
        top: 25%;
    }

    .fast-spin {
        color: #006EB8;
        -webkit-animation: fa-spin .5s infinite linear;
        animation: fa-spin .5s infinite linear;
        margin: 0 auto;
        display: block;
    }
</style>

<div id="product_panel" class="container-fluid pad-xs">
    <div class="row action-row">
        <div class="col-md-12">
            <?php if ($model->store_id == CurrentStore::getStoreId()): ?>
                <a data-toggle="modal" data-target="#delete-product-modal"
                   class="btn btn-danger pull-right left-btn-space">Delete</a>
            <?php endif; ?>
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['form' => "productForm", 'class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']) ?>
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['product/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
        </div>
    </div>
    <div class="panel panel__ui">
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
                'category_ids' => $category_ids,
                'product_type' => $product_type,
                'categoryProductModel' => $categoryProductModel,
                'uploadForm' => $uploadForm,
                'categoriesArray' => $categoriesArray,
                'brandsArray' => $brandsArray,
                'featuresArray' => $featuresArray,
                'featuresOptions' => $featuresOptions,
                'attributes' => $attributes,
                'attributeSetId' => $attributeSetId,
                'isUpdate' => $isUpdate,
                'isOwner' => $isOwner,
                'isChildSimple' => $isChildSimple,
                'isStandaloneSimple' => $isStandaloneSimple,
                'catalogProductFeature' => $catalogProductFeature,
                'catalogProductFeature' => $catalogProductFeature
            ]) ?>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-product-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you wish to delete <?= $model->slug ?>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">No
                </button>
                <button id="delete-product-ajax" data-id="<?= $model->id ?>" action="/admin/product/delete"
                        method="post" class="btn btn-danger" type="submit">Yes
                </button>
            </div>
        </div>
    </div>
</div>

