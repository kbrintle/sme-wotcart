<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CostumerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
?>

    <div class="container-fluid store-index pad-top">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>

        <? $gridColumns = [
            [
                'header' => 'First Name',
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'first_name',
                'value' => 'first_name',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '15%',
                'pageSummary' => true
            ],
            [
                'header' => 'Last Name',
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'last_name',
                'value' => 'last_name',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '15%',
                'pageSummary' => true
            ],
            [
                'header' => 'Email',
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'email',
                'value' => 'email',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '15%',
            ],
            [
                'header' => 'Created',
                'attribute' => 'created_at',
                'format' => ['date', 'php:M d, Y h:m'],
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '15%'
            ],
            [
                'attribute' => 'is_active',
                'filter' => FormHelper::getFilterableBooleanValues(),
                'format' => 'boolean',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '15%'
            ],
            ['header' => 'Actions', 'class' => 'yii\grid\ActionColumn', 'headerOptions' => ['style' => 'width:3%'],
                'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{delete}'
            ]
        ];
        echo GridView::widget([
            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'hover' => true,
            'pjax' => true,
        ]); ?>

    </div>