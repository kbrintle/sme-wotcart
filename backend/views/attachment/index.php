<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */

$this->title = 'Attachments';
?>

<style>
    .glyphicon {
        cursor: pointer;
    }
</style>

<div id="attachments" class="container-fluid pad-xs">
    <div class="attribute-index">
        <? if (empty($attachmentCount)): ?>
            <div class="empty-state text-center">
                <h3>It looks like you don't have any attachments yet</h3>
                <p>To get started, click the 'New Attachment' button below.</p>
                <? echo Html::a('New Attachment', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <? else: ?>
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('New Attachment', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>
        <div class="row">
            <?php $gridColumns = [
              /*  ['header' => 'Select', 'class' => '\kartik\grid\CheckboxColumn',
                'width' => '5%'],*/
                [
                    'attribute' => 'title', 'header' => 'Title',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '30%',
                    'pageSummary' => true
                ],
                [
                    'attribute' => 'file_name', 'header' => 'File',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '30%',
                    'pageSummary' => true
                ],
                ['attribute' => 'is_active', 'header' => 'Active', 'class' => '\kartik\grid\BooleanColumn',
                    'trueLabel' => 'Yes',
                    'falseLabel' => 'No'],
                ['header' => 'Actions', 'class' => 'yii\grid\ActionColumn', 'headerOptions' => ['style' => 'width:10%'],
                    'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{delete}'
                ]
            ]; ?>

            <?= GridView::widget([
                'dataProvider' => $attachmentProvider,
                //'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'hover' => true,
            ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>