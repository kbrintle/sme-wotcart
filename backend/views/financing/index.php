<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel common\models\store\search\StoreFinancingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Store Financing';

?>
<div class="container-fluid pad-xs">
    <div class="store-financing-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?= (!$financing) ? Html::a('Create Store Financing', ['create'], ['class' => 'btn btn-primary pull-right']) : ''?>
                <?= (isset($financing)) ? Html::a('Update Store Financing', ['update', 'id'=>$financing->id], ['class' => 'btn btn-primary pull-right']) : '' ?>
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'store.name',
                [
                    'attribute'=>'is_active',
                    'filter'=>FormHelper::getFilterableBooleanValues(),
                    'format'=> 'boolean',
                ],

                [
                    'label' => '',
                    'value' => function ($data) {
                        $icon = '<i class="material-icons">more_vert</i>';
                        $items = array(
                            'View' => Url::to(['financing/view', 'id' => $data->id]),
                            'Edit' => Url::to(['financing/update', 'id' => $data->id]),
//                            'Delete' => Url::to(['location/delete', 'id' => $data->id])
                        );
                        return FormHelper::moreButton($icon, $items);
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
</div>
