<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\sales\search\SalesQuoteItemSearch;
use common\models\sales\SalesQuote;
use common\components\CurrentStore;

?>
<?php if ($currentQuote = SalesQuote::findOne(["is_active" => '1', "store_id" => CurrentStore::getStoreId(), 'user_id' => $model->id])) : ?>
    <? $searchModel = new SalesQuoteItemSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $currentQuote->id);
    $dataProvider->pagination->pageSize = 15;

    $gridColumns = [
        [
            'header' => 'Slug',
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'product.slug',
            'value' => 'product.slug',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true

        ],
        [
            'header' => 'Sku',
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'sku',
            'value' => 'sku',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true

        ],
        [
            'header' => 'Qty',
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'qty',
            'value' => 'qty',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'pageSummary' => true
        ],
        [
            'header' => 'Price Each',
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'price',
            'value' => 'price',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'format' => ['currency', 'USD'],
            'pageSummary' => true
        ]];

    echo GridView::widget([
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'hover' => true,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => 'reward-points-pjax'
            ]
        ]
    ]);
    ?>
<?php else: ?>
Pick a Store
<?php endif; ?>