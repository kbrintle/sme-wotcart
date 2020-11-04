<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogAttributeSet */

$this->title = 'New Attribute';
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="attribute-index">
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::submitButton('Create', ['class' => 'btn btn-primary pull-right']) ?>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Yii::$app->request->referrer, ['title' => 'Back', 'class' => 'btn btn-secondary btn-spacer pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'form'  => $form
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

