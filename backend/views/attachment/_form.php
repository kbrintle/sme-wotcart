<?php

use common\models\core\Store;
use common\components\CurrentStore;
use common\components\helpers\FormHelper;
use common\models\catalog\search\CatalogProductSearch;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>


<div class="catalog-attachment-form">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#settings" aria-controls="settings" role="tab"
                                                      data-toggle="tab">Base Settings</a></li>
            <?php if (isset($isUpdate)): ?>
              <!--  <li role="presentation"><a href="#product-attachments" aria-controls="settings"
                                           role="tab"
                                           data-toggle="tab">Products</a></li>-->
            <?php endif; ?>
        </ul>
        <br/>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="settings">
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'file_name')->fileInput(); ?>
                    <?php
                    if ($model->file_name) {
                        $file = "<i class='far fa-file'  style='font-size:60px'></i>  ";
                        if (in_array($model->file_type, array('gif', 'jpg', 'jpeg', 'png', 'svg'))) {
                            $file = Html::img("/uploads$model->file_name", ["style" => "max-width:50%;"]);
                        } else if ($model->file_type == "pdf") {
                            $file = "<i class='far fa-file-pdf'  style='font-size:60px'></i>  ";
                        }
                        echo Html::a($file, "/uploads$model->file_name", ['target' => "_blank"]);
                        $explode = explode('/', $model->file_name);
                        echo "<br>" . end($explode) . "<br>";
                    } ?>

                    <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues()); ?>
                    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <? if (isset($isUpdate)): ?>
                <div role="tabpanel" class="tab-pane" id="product-attachments">
                    <?php

         /*           $searchModel = new CatalogProductSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->andFilterWhere([
                        'product.is_deleted' => false
                    ]);

                    //$dataProvider->query->andWhere(['product.store_id' => CurrentStore::getStoreId()]);
                    */?><!--

                    <?/* $gridColumns = [
                        ['header' => 'Select', 'class' => '\kartik\grid\CheckboxColumn',
                            'width' => '5%',
                            'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            'checkboxOptions' => function ($productModel) use ($attachmentProducts, $model) {

                                $bool = in_array($productModel->id, $attachmentProducts);
                                return ['checked' => $bool, 'product_id' => $productModel->id, 'attachment_id' => $model->attachment_id, 'href' => "/admin/product/associate-product-to-attachment"];
                            }],
                        [
                            'header' => 'Brand',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'productBrand',
                            'value' => 'productBrand.name',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '10%',
                            'pageSummary' => true
                        ],
                        [
                            'header' => 'Type',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'type',
                            'value' => 'type',
                            'filterType' => '\kartik\grid\GridView::FILTER_SELECT2',
                            'filter' => [
                                'simple' => 'simple',
                                'child-simple' => 'child-simple',
                                'grouped' => 'grouped'
                            ],
                            'width' => '10%',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                        ],
                        [
                            'header' => 'SKU',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'productSku',
                            'value' => 'productSku.value',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '10%',
                            'pageSummary' => true
                        ],
                        [
                            'header' => 'Name',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'productName',
                            'value' => 'productName.value',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '10%',
                            'pageSummary' => true
                        ],
                        [
                            'header' => 'Price',
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'productPrice',
                            'value' => 'productPrice.value',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '10%',
                            'pageSummary' => true
                        ],
                        ['attribute' => 'is_active', 'header' => 'Active', 'class' => '\kartik\grid\BooleanColumn',
                            'trueLabel' => 'Yes',
                            'width' => '10%',
                            'falseLabel' => 'No']
                    ]; */?>

                    --><?/*= GridView::widget([
                        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'hover' => true,
                        'pjax' => true,
                        'columns' => $gridColumns
                    ]); */?>
                </div>
            <? endif; ?>
        </div>
    </div>
</div>