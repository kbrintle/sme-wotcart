<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->email;
//$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid pad-xs">
    <div class="customer-view">

        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['customer/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            </div>
        </div>

        <div class="panel panel__ui">
            <div class="panel-heading clearfix">
                <h4 class="pull-left"><?= Html::encode($this->title) ?></h4>
                <?= Html::a('Delete', ['delete', 'id' => $model->id, 'store_id' => $model->store_id], [
                    'class' => 'btn btn-text pull-right',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method'  => 'post',
                    ],
                ]) ?>
            </div>
            <div class="panel-body">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'email:email',
                        'first_name',
                        'last_name',
                        'time_zone',
                        'local_store'
                    ],
                ]) ?>
            </div>
        </div>

    </div>
</div>