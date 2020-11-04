<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreFinancing */

$this->title = $model->store->name  ."  Financing ";

?>
<div class="container-fluid pad-xs">
    <div class="store-financing-view">

        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['financing/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
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
                        'store.name',
                        'twenty_four_month_min',
                        'thirty_six_month_min',
                        'forty_eight_month_min',
                        'sixty_month_min',
                        'url:url',
                        'disclaimer_text:ntext',
                        'is_active:boolean',
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</div>