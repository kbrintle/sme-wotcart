<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogCategory */

$this->title = 'Update Catalog Category: ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Catalog Categories', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="catalog-category-update">
        <div class="container-fluid">
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'form'  => $form,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>