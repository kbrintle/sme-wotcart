<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogProductFeature */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Catalog Product Features', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="container-fluid pad-xs">
    <div class="catalog-product-feature-view">

        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['feature/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            </div>
        </div>

        <div class="panel panel__ui">
            <div class="panel-heading clearfix">
                <h4 class="pull-left"><?= Html::encode($this->title) ?></h4>
                <?= Html::a('Delete', ['delete', 'id' => $model->id, 'store_id' => $model->store_id], [
                    'class' => 'btn btn-text pull-right',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
            <div class="panel-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'name',
                        'description:ntext',
                        'created_at',
                        'modified_at',
                        'is_active',
                        'is_deleted',
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>