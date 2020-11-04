<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\core\AdminRole;

/* @var $this yii\web\View */
/* @var $searchModel common\models\core\search\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';

?>
<div class="container-fluid pad-xs">
    <div class="admin-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create User', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'hover' => true,
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'gridview-pjax'
                ]],
            'columns' => [
                [
                    'attribute' => 'roleName',
                    'value' => 'role.name',
                    'header' => 'Role',
                    'filterType' => '\kartik\grid\GridView::FILTER_SELECT2',
                    'filter' => ArrayHelper::map(AdminRole::find()->orderBy('name')->asArray()->all(), 'name', 'name'),
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'email',
                    'value' => 'email',
                    'header' => 'Email',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'first_name',
                    'value' => 'first_name',
                    'header' => 'first name',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                [
                    'attribute' => 'last_name',
                    'value' => 'last_name',
                    'header' => 'last name',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                ],
                ['attribute' => 'is_active', 'header' => 'Active', 'class' => '\kartik\grid\BooleanColumn',
                    'trueIcon'=>"yes",
                    'falseIcon'=>"no",
                    'trueLabel' => 'Yes',
                    'width' => '10%',
                    'falseLabel' => 'No',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',],
                ['header' => 'Actions', 'class' => 'yii\grid\ActionColumn', 'headerOptions' => ['style' => 'width:3%'],
                    'template' => '{update}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{delete}'
                ]
            ],
        ]); ?>
    </div>
</div>