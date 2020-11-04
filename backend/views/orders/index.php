<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use common\models\sales\SalesOrderStatus;
use common\models\core\Store;
use common\components\helpers\FormHelper;
use yii\helpers\Url;
use backend\components\CurrentUser;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sales\search\SalesOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';

?>

<style>
    th {
        text-align: center;
    }

    td {
        text-align: center;
    }

    .kv-drp-dropdown .range-value {
        width: 100%;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .text-muted {
        color: #6f6f6f45;
    }
</style>

<div class="contianer-fluid pad-xs">
    <div class="order-index">
        <?= GridView::widget([
            'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-stripped table-responsive table-condensed',
            ],
            'hover' => true,
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'gridview-pjax'
                ]
            ],
            'columns' => [
                [
                    'attribute' => 'order_id',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'header' => 'Store',
                    'attribute' => 'store_id',
                    'value' => function ($searchModel) {
                        return ($store = Store::getStoreById($searchModel->store_id)) ? $store->name : "";
                    },
                    'filterType' => '\kartik\grid\GridView::FILTER_SELECT2',
                    'filter' => $stores_array = ArrayHelper::map(Store::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'width' => '10%',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'prompt' => 'All'
                    ]
                ],
                [
                    'attribute' => 'customer_firstname',
                    'header' => 'First Name',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'customer_lastname',
                    'header' => 'Last Name',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'header' => 'Purchased On',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'attribute' => 'created_at',
                    'width' => "22%",
                    'value' => function ($searchModel, $index, $widget) {
                        return date("M j, Y h:i A", $searchModel->created_at);
                    },
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => [
                        'useWithAddon' => true,
                        'hideInput' => true,
                        'pluginEvents' => [
                            'cancel.daterangepicker' => "function(ev, picker) { 
                                                           var newURL = removeURLParameter(location.href, 'SalesOrderSearch[created_at]');
                                                         window.history.pushState('object', document.title, newURL); $('.has-error').removeClass('has-error'); 
                                                         var d = $('#salesordersearch-created_at'); $('.range-value').html('<em class=\"text-muted\">'+d.val()+'</em>');
                                                          d.val('');   $.pjax.reload({container:'#gridview-pjax'});/* clear any inputs*/}"
                        ],
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'presetDropdown' => true,
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'format' => 'M j, Y h:i A',
                            'opens' => 'left',
                            'timePicker' => true,
                            'timePickerIncrement' => 30,
                            'locale' => [
                                'cancelLabel' => 'Clear',
                                'format' => 'M j, Y h:i A',
                            ]
                        ]
                    ],
                ],
                [
                    'attribute' => 'grand_total',
                    'value' => 'grand_total',
                    'header' => 'Total',
                    'format' => ['decimal', 2],
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'status',
                    'header' => 'Status',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'filter' => ArrayHelper::map(SalesOrderStatus::find()->orderBy('name')->asArray()->all(), 'order_status_id', 'name'),
                    'value' => function ($data) {
                        $status = SalesOrderStatus::findOne($data->status);
                        return $status ? $status->name : '';
                    }
                ],
                [
                    'label' => '',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $status = SalesOrderStatus::findOne($data->status);
                        if ($status) {
                            switch ($status->name) {
                                case 'Pending':
                                    if (!CurrentUser::isOperations()) {
                                        return Html::a('Approve', Url::to([
                                            'orders/process',
                                            'id' => $data->order_id,
                                            'status' => 'accept'
                                        ]), ['class' => 'btn btn-primary']);
                                    } else {
                                        return '';
                                    }
                                    break;
                                case 'Processing':
                                    if (!CurrentUser::isStoreAdmin()) {
                                        return Html::a('Mark Shipped', Url::to([
                                            'orders/process',
                                            'id' => $data->order_id,
                                            'status' => 'complete'
                                        ]), ['class' => 'btn btn-sm btn-secondary']);
                                    } else {
                                        return '';
                                    }
                                    break;

                                default:
                                    return '';
                            }
                        }
                    }
                ],
                [
                    'label' => '',
                    'value' => function ($data) {
                        $icon = '<i class="material-icons">more_vert</i>';
                        $items = array(
                            'View' => Url::to(['orders/view', 'id' => $data->order_id]),
                            //'Edit' => Url::to(['orders/update', 'id' => $data->order_id]),
                            //'Delete' => Url::to(['orders/delete', 'id' => $data->order_id])
                        );
                        return FormHelper::moreButton($icon, $items);
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
</div>

<script>
    function removeURLParameter(url, parameter) {
        //prefer to use l.search if you have a location/link object
        var urlparts = url.split('?');
        if (urlparts.length >= 2) {

            var prefix = encodeURIComponent(parameter) + '=';
            var pars = urlparts[1].split(/[&;]/g);

            //reverse iteration as may be destructive
            for (var i = pars.length; i-- > 0;) {
                //idiom for string.startsWith
                if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                    pars.splice(i, 1);
                }
            }

            url = urlparts[0] + (pars.length > 0 ? '?' + pars.join('&') : "");
            return url;
        } else {
            return url;
        }
    }
</script>
