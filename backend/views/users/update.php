<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\core\Admin */

$this->title = 'Update User'
?>
<div class="container-fluid pad-xs">
    <div class="admin-update">
        <div class="row action-row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin(); ?>
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['users/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right btn-spacer' : 'btn btn-primary pull-right btn-spacer']) ?>

            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'form'  => $form,
                    'id' => $id,
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>