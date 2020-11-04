<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\components\helpers\FormHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CostumerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Leads';
?>

<div class="container-fluid store-index pad-top">
    <div class="row action-row">
        <div class="col-md-12">
            <?= Html::a('Create Lead', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-stripped table-responsive table-condensed',
        ],
        'filterModel' => $searchModel,
        'rowOptions'   => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
        'columns' => [
            'practitioner_name',
            'clinic_name',
            'clinic_position',
            'clinic_phone',
            'contact_email',
//            'group.name',

            [
                'label' => '',
                'value' => function ($data) {
                    $icon = '<i class="material-icons">more_vert</i>';
                    $items = array(
                        'View' => Url::to(['lead/view', 'id' => $data->id]),
                        'Edit' => Url::to(['lead/update', 'id' => $data->id]),
                        'Delete' => Url::to(['lead/delete', 'id' => $data->id])
                    );
                    return FormHelper::moreButton($icon, $items);
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>


<?php
$this->registerJs("

    $('td').click(function (e) {
        var id = $(this).closest('tr').data('id');
        if(e.target == this)
            location.href = '" . Url::to(['customer/update']) . "?id=' + id;
    });

");
