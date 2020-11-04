<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\components\CurrentStore;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel common\models\CostumerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


if(CurrentStore::getStoreId() != 0):?>
    <div class="container-fluid import-index pad-top">
        <div class="row action-row">
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-heading clearfix">
                        <h4>This can only be executed in "All" stores mode.</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else:
    $catalogBrands = ArrayHelper::map(\common\models\catalog\CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->orderBy(['name'=>SORT_ASC])->all(), 'id', 'name');
    $catalogCategories = ArrayHelper::map(\common\models\catalog\CatalogCategory::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');
    $this->title = "Pricing Import / Export";
    ?>

    <div class="container-fluid import-index pad-top">
        <div class="row action-row">

            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-heading clearfix">
                        <h4 class="pull-left">Product Group Import</h4>
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

                        <?php $form = ActiveForm::begin() ?>

                        <div class="field pad-btm-sm">
                            <?= $form->field($upload, 'file')->fileInput()->label('Select File') ?>
                            <?= $form->field($upload, 'group_id')->dropDownList(ArrayHelper::map(\common\models\core\StoreGroup::find()->all(), 'id', 'name'), ['prompt'=>'Select A Group', 'class'=>"pad-btm-sm form-control"])->label("Group") ?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Upload', ['class' => 'margin-top btn btn-primary']) ?>
                        </div>
                        <?php ActiveForm::end() ?>

                    </div>
                </div>
            </div>

<!--            --><?php //if ($attributeSets): ?>
<!--                <div class="col-md-12">-->
<!--                    <div class="panel panel__ui">-->
<!--                        <div class="panel-heading clearfix">-->
<!--                            <h4 class="pull-left">Product Export</h4>-->
<!--                        </div>-->
<!---->
<!--                        <div class="panel-body">-->
<!--                            --><?//= Html::beginForm(['/import/pricing-group-export'], 'POST'); ?>
<!---->
<!--                            <div class="field pad-btm-sm">-->
<!--                                <label>Brand</label>-->
<!--                                --><?//= Html::dropDownList('brand_id', null, $catalogBrands, ['multiple'=>'multiple','prompt'=>'All', 'class'=>"form-control"]) ?>
<!--                            </div>-->
<!---->
<!---->
<!--                            <div class="field pad-btm-sm">-->
<!--                                <label>Category</label>-->
<!--                                --><?//= Html::dropDownList('category', null, $catalogCategories , ['prompt'=>'All', 'class'=>"form-control"]) ?>
<!--                            </div>-->
<!---->
<!--                            <div class="form-group">-->
<!--                                --><?//= Html::submitButton('Export', ['class' => 'btn btn-primary']) ?>
<!--                            </div>-->
<!---->
<!--                            --><?//= Html::endForm() ?>
<!---->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php //endif; ?>
<!---->
<!--        </div>-->
    </div>
<?php endif; ?>