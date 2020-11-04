<?php

use yii\helpers\Html;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = 'Create Customer';

?>

<div class="container-fluid pad-xs">
    <div class="customer-update">

        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['customer/index']), ['title' => 'Back', 'title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>               </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'isCreate' => $isCreate
                ]) ?>

            </div>
        </div>
    </div>
</div>
