<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CostumerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$catalogBrands = ArrayHelper::map(\common\models\catalog\CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->orderBy(['name'=>SORT_ASC])->all(), 'id', 'name');
$catalogCategories = ArrayHelper::map(\common\models\catalog\CatalogCategory::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');
$catalogSets   = ArrayHelper::map(\common\models\catalog\CatalogAttributeSet::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'label');


$this->title = "Import/Export";
?>

<div class="container-fluid import-index pad-top">
    <div class="row action-row">

        <div class="col-md-12">
            <div class="panel panel__ui">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left">Product Import</h4>
                    <?php echo Html::a('Download Template', ['template'], ['class' => 'btn btn-sm btn-secondary pull-right']); ?>
                </div>
                <div class="panel-body">
                    <?php if (isset($result)): ?>
                        <div class="alert alert-success clearfix">
                            <span class="pull-left" style="margin-top: 6px;"><?= $result['status']; ?></span>
                            <?php if (isset($result['errors'])): ?>
                                <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#import-status">Show Errors</a>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($result['errors'])): ?>
                            <div id="import-status" class="collapse">
                                <pre style="overflow: auto; height: 150px;">
                                    <?php foreach ($result['errors'] as $message): ?>
                                        <?php echo $message; ?><br />
                                    <?php endforeach; ?>
                                </pre>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php echo $this->render('upload', [
                        'model' => $upload
                    ]); ?>
                </div>
            </div>
        </div>

        <?php if ($attributeSets): ?>
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-heading clearfix">
                        <h4 class="pull-left">Product Export</h4>
                    </div>

                    <div class="panel-body">
                        <?= Html::beginForm(['/import/export'], 'POST'); ?>

                        <div class="field pad-btm-sm">
                            <label>Brand</label>
                            <?= Html::dropDownList('brand_id', null, $catalogBrands, ['multiple'=>'multiple', 'prompt'=>'All', 'class'=>"form-control"]) ?>
                        </div>

                        <div class="field pad-btm-sm">
                            <label>Category</label>
                            <?= Html::dropDownList('category_id', null, $catalogCategories, ['multiple'=>'multiple', 'prompt'=>'All', 'class'=>"form-control"]) ?>
                        </div>

                        <div class="field pad-btm-sm">
                            <label>Attribute Set</label>
                            <?= Html::dropDownList('attribute_id', null, $catalogSets , ['prompt'=>'All', 'class'=>"form-control"]) ?>
                        </div>

                        <div class="field pad-btm-sm">
                            <label>Product Type</label>
                            <?= Html::dropDownList('type_id', null, [''=>'All', 'configurable'=>'configurable', 'grouped'=>'grouped', 'child-simple'=>'child-simple', 'simple'=>'simple'], ['class'=>"form-control"]) ?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Export', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>