<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\helpers\FormHelper;
use common\models\catalog\CatalogProduct;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogProductReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Reviews';
?>
<div class="contianer-fluid pad-xs">
    <div class="order-index">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => 'Product Name',
                    'value' => function ($data) {
                        $product_id = $data->product_id;
                        return CatalogProduct::getName($product_id);
                    }
                ],
                'rating',
                'title',
                //'detail:ntext',
                'customer.fullname',
                [
                    'label'=>'Store',
                    'attribute'=> 'store.name'
                ],
                'approved:boolean',
                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:m/d/Y']
                ],

                [
                    'label' => '',
                    'value' => function ($data) {
                        $icon = '<i class="material-icons">more_vert</i>';
                        $items = array(
                            'View' => Url::to(['reviews/view', 'id' => $data->id]),
                            'Delete' => Url::to(['reviews/delete', 'id' => $data->id])
                        );
                        return FormHelper::moreButton($icon, $items);
                    },
                    'format' => 'raw',
                ],
            ],
        ]); ?>
    </div>
</div>