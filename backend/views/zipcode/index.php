<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\core\Store;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Store Zip Codes';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid store-index pad-top">
    <div class="row action-row">
        <div class="col-md-12">
            <?= Html::a('Create Store Zip Code', ['create'], ['class' => 'btn btn-primary pull-right', 'style' => 'margin-left: 5px;']) ?>
            <?= Html::a('Import/Export', ['import'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php Pjax::begin([
                'timeout' => '6000'
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-responsive table-condensed',
                ],
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'store',
                        'value' => 'store.name'
                    ],
                    'zip_code',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]) ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>