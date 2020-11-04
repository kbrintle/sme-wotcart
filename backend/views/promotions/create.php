<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'New Targeted Promotion';
?>
<?php $form = ActiveForm::begin(); ?>
<div class="container-fluid pad-xs">
    <div class="promotion-new">
        <div class="row">
            <div class="col-md-12">
                <div class="row action-row">
                    <div class="col-md-12">
                        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                        <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['/promotions']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                    </div>
                </div>
                <div class="panel panel__ui">
                    <div class="panel-body">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'form'  => $form
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>