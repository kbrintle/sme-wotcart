<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\store\NewsletterSubscriber */

$this->title = $model->id;

?>
<div class="newsletter-subscriber-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'store_id',
            'change_status_at',
            'customer_id',
            'email:email',
            'is_active',
            'confirm_code',
            'created_time:datetime',
        ],
    ]) ?>

</div>