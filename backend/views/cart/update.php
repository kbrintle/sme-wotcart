<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = "Update Cart #$model->id";
?>

<div class="container-fluid pad-xs">
    <div class="cart-update">

        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['cart/index']), ['title' => 'Back', 'class' => 'btn btn-default pull-right']); ?>
            </div>
        </div>

        <div class="panel panel__ui">
            <div class="panel-heading">
                <h4><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="panel-body">

                Update Cart #<?php echo $model->id; ?>

            </div>
        </div>

    </div>
</div>

