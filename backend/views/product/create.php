<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\Product */

$this->title = 'Create Product';
$back_url = ($product_type == "child-simple") ? "product/parent?tid=$product_type" : "product/attributes?tid=$product_type";
?>

    <div class="container-fluid pad-xs">
        <div class="row action-row">
            <div class="col-md-12">
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
                    'catalogProductFeature' => $catalogProductFeature,
                    'categoryProductModel' => $categoryProductModel,
                    'uploadForm' => $uploadForm,
                    'categoriesArray' => $categoriesArray,
                    'brandsArray' => $brandsArray,
                    'featuresArray' => $featuresArray,
                    'featuresOptions' => $featuresOptions,
                    'attributes' => $attributes,
                    'isCreate' => $isCreate,
                    'isChildSimple' => $isChildSimple,
                    'isStandaloneSimple' => $isStandaloneSimple
                ]) ?>
            </div>
        </div>
    </div>