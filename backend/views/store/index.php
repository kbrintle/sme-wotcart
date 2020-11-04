<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\core\StoreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stores';
//$this->params['breadcrumbs'][] = $this->title;
?>

<section>
    <div class="container-fluid store-index pad-xs">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Store', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
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
                        'name',
                        'url',
                        [
                            'label' => 'Group',
                            'attribute' => 'group.name',
                            'filter' => ArrayHelper::map(\common\models\core\StoreGroup::find()->all(), 'id', 'name'),
                        ],

                        [
                            'attribute' => 'is_active',
                            'filter' => FormHelper::getFilterableBooleanValues(),
                            'format' => 'boolean',
                        ],
                        [
                            'label' => '',
                            'value' => function ($data) {
                                $icon = '<i class="material-icons">more_vert</i>';
                                $items = array(
                                    'Edit' => Url::to(['store/update', 'id' => $data->id]),
                                    'Delete' => Url::to(['store/delete', 'id' => $data->id])
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
