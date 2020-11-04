<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Store */

$this->title = $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Stores', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="store-view">
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']) ?>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['store/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
                           // 'id',
                            'name',
                            'url:url',
                           // 'created_at',
                           // 'updated_at',
                            'is_active:boolean',
                          //  'is_deleted:boolean',
                           // 'is_default',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
