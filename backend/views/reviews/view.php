<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\catalog\CatalogProduct;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogProductReview */

$this->title = $model->title;

?>
<div class="contianer-fluid pad-xs">

        <div class="row action-row pull-right">
            <div class="col-md-12">
            <?= (!$model->approved) ? Html::a('Approve', ['approve', 'id' => $model->id], ['class' => 'btn btn-primary ']) : Html::a('Disapprove', ['approve', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            </div>
        </div>


        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Product Name',
                    'value' => function ($data) {
                        $product_id = $data->product_id;
                        return CatalogProduct::getName($product_id);
                    }
                ],
                'rating',
                'title',
                'detail:ntext',
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

            ],
        ]) ?>

    </div>
</div>