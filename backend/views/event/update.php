<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = 'Update Event: ' . $model->title;

?>
<?php
$form = ActiveForm::begin([
    'id'         => 'event-update',
    'options'   => [
        'class'     => 'form',
        'enctype'   => 'multipart/form-data'
    ]
]);
?>
    <div class="container-fluid pad-xs">
        <div class="blog-update">
            <div class="row action-row">
                <div class="col-md-12">
                    <?php if( $model->is_active == 0 ): ?>
                        <?= Html::submitButton('Publish', [
                            'class' => 'btn btn-primary pull-right',
                            'name'  => 'action',
                            'value' => 'Publish'
                        ]); ?>
                    <?php endif; ?>

                    <?php if( $model->is_active == 1 ): ?>
                        <?= Html::submitButton('Update', [
                            'class' => 'btn btn-primary pull-right',
                            'name'  => 'action',
                            'value' => 'Update'
                        ]); ?>
                    <?php endif; ?>

                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['event/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model'         => $model,
                        'form'          => $form,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
