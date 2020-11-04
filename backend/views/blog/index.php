<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog';
//$this->params['breadcrumbs'][] = $this->title;
?>

<section>
    <div class="container-fluid blog-index pad-xs">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Blog', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
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
                            'title',
                            //'post_content:ntext',
                            [
                                'attribute'=>'status',
                                'filter'=>FormHelper::getFilterableBooleanValues(),
                                'format'=> 'boolean',
                            ],
                            [
                                'attribute' => 'created_time',
                                'format' => ['date', 'php:M d, Y']
                            ],

                            // 'update_time',
                            // 'identifier',
                            // 'user',
                            // 'update_user',
                            // 'meta_keywords:ntext',
                            // 'meta_description:ntext',
                            // 'comments',
                            // 'tags:ntext',
                            // 'short_content:ntext',
                            
                            [
                                'label' => '',
                                'value' => function ($data) {
                                    $icon = '<i class="material-icons">more_vert</i>';
                                    $items = array(
                                        'View' => Url::to(['blog/view', 'id' => $data->post_id]),
                                        'Edit' => Url::to(['blog/update', 'id' => $data->post_id]),
                                        'Delete' => Url::to(['blog/delete', 'id' => $data->post_id])
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