<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogAttribute */
/* @var $form yii\widgets\ActiveForm */


$attribute_options = $model->attributeOptions;
?>

<div class="catalog-attribute-form">

    <div class="col-md-6">
        <?= $form->field($model, 'label')->textInput(['maxlength' => true]); ?>
        <?= $form->field($model, 'category_id')->dropdownList($model->setCategories, ['prompt'=>'Select one']); ?>

        <?= $form->field($model, 'is_filterable')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>
        <div id="filter-sort">
            <?= $form->field($model, 'filter_sort')->textInput(); ?>
        </div>
        <?= $form->field($model, 'is_product_view')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one'])->label("Display On Product View?"); ?>
        <div id="view-sort">
            <?= $form->field($model, 'product_view_sort')->textInput(); ?>
        </div>
        <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>
    </div>

    <div class="col-md-6">
        <?php if (!isset($update)): ?>
            <?= $form->field($model, 'type_id')->dropdownList($model->attributeTypes, ['prompt'=>'Select one']); ?>
        <?php endif; ?>
        <div id="select-attribute" class="panel panel-default <?php if( !$model->isSelectable() ): ?> hidden <?php endif; ?>">
            <div class="panel-body">

                <div id="select-options">
                    <div id="sortable">
                        <?php if( count($attribute_options) > 0 ): ?>
                            <?= $this->render('partials/_has_options', [
                                'model'             => $model,
                                'attribute_options' => $attribute_options
                            ]); ?>
                        <?php else: ?>
                            <?= $this->render('partials/_no_options', [
                                'model'             => $model,
                                'attribute_options' => $attribute_options
                            ]); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <br />
                <button type="button" id="add-option" data-index='1' class="btn btn-primary">Add Option</button>
            </div>

        </div>
        <?= $form->field($model, 'visible_on')->dropdownList(FormHelper::getAttributeVisibilityOptions(), ['prompt' => 'Select one']); ?>
        <?= $form->field($model, 'is_editable')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>
    </div>

</div>