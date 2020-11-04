<?php

use dosamigos\fileupload\FileUploadUI;
use kartik\grid\GridView;
use yii\helpers\Html;
use \common\models\catalog\CatalogProductGallery;
use \common\models\catalog\search\CatalogProductGallerySearch;

$catalogProductGallery = new CatalogProductGallery();
$searchModel = new CatalogProductGallerySearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->id);
$dataProvider->pagination->pageSize = 15;
$count = number_format($dataProvider->getTotalCount(),0);

?>

<style>
    a img {
        max-width: 350px;
        max-height: 200px;
    }

    .template-download td {
        text-align: center;
    }

    .modal-lg {
        width: 1052px;
    }

    .btn-space {
        margin-right: 5px;
    }

    #fileupload input.toggle, .fileupload-buttonbar input.toggle {
        display: none
    }
</style>

<div class="container-fluid pad-xs">
    <div class="product-media">
        <div class="panel panel__ui">
            <div class="panel-body">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#image-upload">Upload New Images</button>
                <?= Html::button('Delete', ['id' =>"deleteBtn", 'title' => 'Delete Selected', 'class' => 'pull-right btn-sm btn-danger hidden', 'href' => "/admin/product/image-delete"]); ?>
                <?= Html::button('Set Base Image', ['id' =>"setDefaultBtn", 'title' => 'Set Base Image', 'class' => 'btn-sm btn-success pull-right btn-space hidden', 'href' => "/admin/product/set-default-image"]); ?>
                <br> <br>

                <?= GridView::widget([
                    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'hover' => true,
                    'pjax' => true,
                    'pjaxSettings' =>[
                        'neverTimeout'=>true,
                        'options'=>[
                            'id'=>'gridview-pjax'
                        ]
                    ],
                    'columns' => [
                        ['header' => 'Select', 'class' => '\kartik\grid\CheckboxColumn',
                            'width' => '5%',
                            'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            'checkboxOptions' => function ($gallaryModel) use ($model) {
                                return ['id' => $gallaryModel->id, 'data-pid' => $model->id, 'value' => $gallaryModel->value ];
                            }
                        ],
                        [
                            'attribute' => 'img',
                            'format' => 'html',
                            'value' => function ($GalleryModel) {
                                return Html::img('/uploads/products/'. $GalleryModel->value, ['width' => '250px']);
                            },
                            'header' => 'Image',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '45%',

                        ],

                        ['class' => '\kartik\grid\DataColumn',
                            'attribute' => 'value',
                            'value' => 'value',
                            'header' => 'File Name',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                        ],
                        ['attribute' => 'is_default',
                            'header' => 'Base Image',
                            'class' => '\kartik\grid\BooleanColumn',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'trueLabel' => 'Yes',
                            'falseLabel' => 'No'],
                        ['class' => '\kartik\grid\EditableColumn',
                            'attribute' => 'sort',
                            'value' => 'sort',
                            'header' => 'Order',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                        ]
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="image-upload">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Upload New Images</h4>
            </div>
            <div class="modal-body">
                <?= FileUploadUI::widget([
                    'model' => $catalogProductGallery,
                    'attribute' => 'value',
                    'url' => ['product/image-upload','id' => $model->id],
                    'gallery' => false,
                    'fieldOptions' => [
                        'accept' => 'image/*'
                    ],
                    'clientOptions' => [
                        'maxFileSize' => 2000000
                    ],
                    // ...
                    'clientEvents' => [
                        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
                        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
                    ],
                ]); ?>
            </div>
            <div class="modal-footer">
                <button id="done" type="button" class="btn btn-secondary" data-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<script>

</script>