<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid pad-xs">
    <div class=" location-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Location', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>

        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-stripped table-responsive table-condensed',
                ],
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute'=> 'store.name',
                        'label'=> 'Account',
                    ],
                    [
                        'attribute'=> 'name',
                        'label'=> 'Location',
                    ],
                    //'type',
                    'address',
                    'city',
                    'state',
                    //'email:email',
//                    [
//                        'attribute'=>'is_active',
//                        'filter'=>FormHelper::getFilterableBooleanValues(),
//                        'format'=> 'boolean',
//                    ],
                    [
                        'label' => '',
                        'value' => function ($data) {
                            $icon = '<i class="material-icons">more_vert</i>';
                            $items = array(
                                //'View' => Url::to(['location/view', 'id' => $data->id]),
                                'Edit' => Url::to(['location/update', 'id' => $data->id]),
                                'Delete' => Url::to(['location/delete', 'id' => $data->id])
                            );
                            return FormHelper::moreButton($icon, $items);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>