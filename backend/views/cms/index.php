<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

$this->title = 'CMS';
?>


<div class="container-fluid">
    <div class="row action-row">
        <div class="col-md-12">
            <?= Html::a('Create CMS Page', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-stripped table-responsive table-condensed',
            ],
            'columns' => [
                'title',
               // 'template',
                'url_key',
                //'content',
                //'meta_description',
                //'meta_keywords',
                //'author_id',
                'created_time:datetime',
                //'modified_time:datetime',
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
                            'Edit' => Url::to(['cms/update', 'id' => $data->id]),
                            'Delete' => Url::to(['cms/delete', 'id' => $data->id])
                        );
                        return FormHelper::moreButton($icon, $items);
                    },
                    'format' => 'raw',
                ],
            ],
        ]) ?>
    <?php Pjax::end(); ?>
</div>