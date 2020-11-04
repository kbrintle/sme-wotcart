<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogBrand */

$this->title = 'Create Catalog Brand';
//$this->params['breadcrumbs'][] = ['label' => 'Catalog Brands', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="container-fluid pad-xs">
    <div class="catalog-brand-create">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary']) ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['brand/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-heading">
                <h4>New Category</h4>
            </div>
            <div class="panel-body">

                <?= $this->render('_form', [
                    'model' => $model,
                    'form'  => $form
                ]) ?>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
