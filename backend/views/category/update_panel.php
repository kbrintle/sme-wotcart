<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-category-update" categoryId="<?= $model->id; ?>">
    <div class="container-fluid">
        <div class="panel panel__ui">
            <div class="panel-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#base" role="tab" data-toggle="tab">
                            Update
                        </a></li>
                    <li role="presentation">
                        <a href="#associated" role="tab" data-toggle="tab">Associated Products</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="associated">
                        <br>
                        <?php echo Yii::$app->controller->renderPartial('partials/_associatedproducts', ['model' => $model, 'catalogBrands' => $catalogBrands]); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="base">
                        <?php echo Yii::$app->controller->renderPartial('partials/_form', ['model' => $model]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
