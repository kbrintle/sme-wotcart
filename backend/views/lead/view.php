<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\core\Subregions;

/* @var $this yii\web\View */
/* @var $model common\models\Lead */

$this->title = "Lead View: $model->clinic_name";
?>
<div class="container-fluid pad-xs">
    <div class="customer-view">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['lead/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-default pull-right',
                    'style' => 'margin-left:10px;',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Convert to Customer', ['convert', 'id' => $model->id], [
                    'class' => 'btn btn-primary pull-right',
                    'data' => [
                        'confirm' => 'Are you sure you want to convert this lead?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-body">
                <?php
//                    echo $model->clinic_state; die;
//                    $model->clinic_state = Subregions::find()->where(["id" => $model->clinic_state])->one()->name;
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'practitioner_name',
                        'clinic_name',
                        'clinic_position',
                        'ordering_contact_name',
                        'clinic_address',
                        'clinic_city',
                        'clinic_state',
                        'clinic_zip',
                        'clinic_phone',
                        'clinic_fax',
                        'clinic_email',
                        'contact_email',
                        'website',
                        'network_member_list',
                        'how_hear',
                        'top_five'
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>