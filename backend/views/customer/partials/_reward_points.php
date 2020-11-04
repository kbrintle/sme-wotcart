<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\customer\CustomerReward;
use common\models\customer\search\CustomerRewardSearch;

$searchModel = new CustomerRewardSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);
$dataProvider->pagination->pageSize = 15;
$customerReward = new CustomerReward;

?>
Available Rewards points: <span id="usable-points"> <?= CustomerReward::getUsablePoints($model->id); ?></span>
<?= Html::button('Add/Subtract Points', ['class' => 'pull-right btn btn-primary', 'data-toggle' => "modal", 'data-target' => "#reward-points-modal"]); ?>
<br><br>
<?php
$gridColumns = [
    [
        'header' => 'order',
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'order_id',
        'value' => 'order_id',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        //'pageSummary' => true
    ],
    [
        'header' => 'Points',
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'points',
        'value' => 'points',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'pageSummary' => true
    ],
    [
        'header' => 'Comments',
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'comments',
        'value' => 'comments',
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'pageSummary' => true
    ],

//    [
//        'header' => 'add/sub',
//        'class' => '\kartik\grid\DataColumn',
//        'attribute' => 'type',
//        'value' => 'type',
//        'filterType' => '\kartik\grid\GridView::FILTER_SELECT2',
//        'filter' => [
//            'add' => 'add',
//            'sub' => 'sub'
//        ],
//        'width' => '10%',
//        'hAlign' => 'center',
//        'vAlign' => 'middle',
//    ],
    [
        'header' => 'Date-Time',
        'attribute' => 'created_at',
        'format' => ['date', 'php:M d, Y h:m'],
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'width' => '15%'
    ],
];
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
]); ?>


<div class="modal modal__ui fade" id="reward-points-modal" tabindex="-1" role="dialog" aria-labelledby="brandsModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="brandsModalLabel">Add/Subtract Points</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="brand-img">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php $form = ActiveForm::begin([
                            'action' => '/admin/customer/ajax-reward-points',
                            'id' => 'reward-points-form',
                        ]); ?>

                        <?= $form->field($customerReward, 'points')->textInput(); ?>

                        <?= $form->field($customerReward, 'customer_id')->hiddenInput(['value' => $model->id])->label(false); ?>

                        <?= Html::button("Save", ["id" => 'reward_points_save', 'class' => 'btn btn-primary pull-right']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>