<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Location */

$this->title = 'Create Location';
//$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="location-create">
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary']) ?>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['location/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="panel-body">

                    <?= $this->render('_errors', [
                        'errors' => $model->errors,
                    ]) ?>

                    <?= $this->render('_form', [
                        'model' => $model,
                        'form'  => $form,
                    ]) ?>

                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4>Set Store Hours</h4>
                </div>
                <div class="panel-body">
                    <?= $this->render('partials/hours-selector', [
                        'form'  => $form,
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>