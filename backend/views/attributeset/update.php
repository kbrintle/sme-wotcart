<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogAttributeSet */

$this->title = 'Update Catalog Attribute Set: ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Catalog Attribute Sets', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="catalog-attribute-set-update">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary pull-right']) ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['attributeset/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
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
<?php ActiveForm::end(); ?>
