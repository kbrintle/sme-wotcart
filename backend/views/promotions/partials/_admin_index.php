<?php
    use yii\grid\GridView;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-stripped table-responsive table-condensed',
    ],
    'columns' => [
        'store_id',
        'store.name',
        'starts_at:date',
        'ends_at:date',
        'created_at:datetime',
        'modified_at:datetime'
    ],
]); ?>
