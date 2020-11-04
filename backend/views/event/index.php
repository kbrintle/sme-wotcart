<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
//$this->params['breadcrumbs'][] = $this->title;
?>

<section>
    <div class="container-fluid blog-index pad-xs">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Event', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php Pjax::begin(); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => [
                            'class' => 'table table-stripped table-responsive table-condensed',
                        ],
                        'columns' => [
                            [
                                'attribute' => 'event_start_date',
                                'format' => ['date', 'php:M d, Y h:i a']
                            ],
                            [
                                'attribute' => 'event_end_date',
                                'format' => ['date', 'php:M d, Y h:i a']
                            ],
                            'title',
                            //'post_content:ntext',


//                            [
//                                'attribute' => 'created_at',
//                                'format' => ['date', 'php:M d, Y']
//                            ],
                            [
                                    'label'=>'Active',
                                    'attribute'=>'is_active',
                                'filter'=>FormHelper::getFilterableBooleanValues(),
                                'format'=> 'boolean',
                            ],

                            
                            [
                                'label' => '',
                                'value' => function ($data) {
                                    $icon = '<i class="material-icons">more_vert</i>';
                                    $items = array(
                                        'Edit' => Url::to(['event/update', 'id' => $data->id]),
                                        'Delete' => Url::to(['event/delete', 'id' => $data->id])
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
    </div>
</section>