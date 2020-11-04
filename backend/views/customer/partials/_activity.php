<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\components\helpers\FormHelper;
use common\models\customer\search\CustomerActivitySearch;

$searchModel = new CustomerActivitySearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->id);
$dataProvider->pagination->pageSize = 15;
$gridColumns = [
    [
        'header' => 'data',
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'data',
        'value' => 'data',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'pageSummary' => true
    ],
    [
        'header' => 'ip',
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ip',
        'value' => 'ip',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'pageSummary' => true
    ],
    [
        'header' => 'Date-Time',
        'attribute' => 'created_at',
        'format' => ['date', 'php:M d, Y h:m'],
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '15%'
    ]
];
echo GridView::widget([
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'hover' => true,
    'pjax' => true,
]);