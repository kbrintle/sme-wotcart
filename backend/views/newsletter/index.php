<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\store\search\NewsletterSubscriber */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Newsletter Subscribers';

?>
<div class="contianer-fluid pad-xs">
    <div class="">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Export Newsletter Subscribers', ['export'], ['class' => 'btn btn-success pull-right']) ?>
            </div>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            [
//                'attribute'=>'store.name',
//                'label'=>'Store'
//            ],
            'email:email',
           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
        </div>
    </div>
