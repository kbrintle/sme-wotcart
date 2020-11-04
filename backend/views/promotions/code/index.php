<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\helpers\FormHelper;
use common\components\CurrentStore;
use common\models\promotion\PromotionStoreCode;

/* @var $this yii\web\View */
/* @var $searchModel common\models\promotion\search\PromotionCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Discount Codes';
?>
<div class="container-fluid pad-xs">
    <div class="promotion-codes-index">

        <?php if (empty($codes)): ?>
            <div class="empty-state text-center">
                <!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have any Discount Codes yet</h3>
                <p>To get started, click the 'New Discount Code' button below.</p>
                <?= Html::a('New Discount Code', ['create-code'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::a('New Discount Code', ['create-code'], ['class' => 'btn btn-primary pull-right']); ?>
                </div>
            </div>
            <div class="row">
                <?php if (CurrentStore::isNone()): ?>
                    <div class="col-md-12">
                        <?php Pjax::begin(); ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => [
                                'class' => 'table table-stripped table-responsive table-condensed',
                            ],
                            'columns' => [
                                'id',
                                'code',
                                'type',
                                'event',
                                'amount',
                                'starts_at:datetime',
                                'ends_at:datetime',
                                [
                                    'label' => '',
                                    'value' => function ($data) {
                                        $icon = '<i class="material-icons">more_vert</i>';
                                        $items = array(
                                            //'View'   => Url::to(['promotions/viewcode', 'id' => $data->id]),
                                            'Edit' => Url::to(['promotions/update-code', 'id' => $data->id]),
                                            'Delete' => Url::to(['promotions/delete-code', 'id' => $data->id]),
                                        );
                                        return FormHelper::moreButton($icon, $items);
                                    },
                                    'format' => 'raw',
                                ],
                            ],
                        ]); ?>
                        <?php Pjax::end(); ?>
                    </div>
                <?php else: ?>
                    <div class="col-md-12">
                        <div class="panel panel__ui">
                            <div class="panel-heading">
                                <h4>Available Discount Codes</h4>
                            </div>
                            <div class="panel-body">
                                <?php foreach ($codes as $code): ?>
                                    <?php $active = PromotionStoreCode::findOne([
                                        'code_id' => $code->id,
                                        'store_id' => CurrentStore::getStoreId()
                                    ]) ? true : false; ?>
                                    <div class="row">
                                        <div class="available-brands">
                                            <div class="col-md-4 clearfix">
                                                <div class="pull-left">
                                                    <h3 class="brand-title"><?= $code->code; ?></h3>
                                                    <?= Html::a('View Code', ['update-code', 'id' => $code->id], ['class' => '']); ?>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="text-left">
                                                    <b>Discount: <?= $code->type === 'Percentage' ? "$code->amount%" : ($code->type === 'Fixed Amount' ? '$' . number_format((float)$code->amount, 2) : "Free Product(s)"); ?></b>
                                                    <br/>
                                                    <span class="text-muted">
                                                        <small>
                                                            Valid from <?= date('m/d/Y', strtotime($code->starts_at)); ?> to <?= date('m/d/Y', strtotime($code->ends_at)); ?>
                                                        </small>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <div class="brand-action">
                                                    <?php if ($active): ?>
                                                        <?= Html::a('Disable', [
                                                            'enable-code',
                                                            'cid' => $code->id,
                                                            'action' => 'disable',
                                                        ], ['class' => 'btn btn-secondary']); ?>
                                                    <?php else: ?>
                                                        <?= Html::a('Enable', [
                                                            'enable-code',
                                                            'cid' => $code->id,
                                                            'action' => 'enable',
                                                        ], ['class' => 'btn btn-primary']); ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</div>