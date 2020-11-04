<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = 'Update Blog: ' . $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->post_id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<?php
$form = ActiveForm::begin([
    'id'         => 'blog-update',
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
                    <?php if( $model->status == 0 ): ?>
                        <?= Html::submitButton('Publish', [
                            'class' => 'btn btn-primary pull-right',
                            'name'  => 'action',
                            'value' => 'Publish'
                        ]); ?>
                        <?= Html::submitButton('Preview', [
                            'class' => 'btn btn-default pull-right',
                            'name'  => 'action',
                            'value' => 'Preview'
                        ]); ?>
                    <?php endif; ?>

                    <?php if( $model->status == 1 ): ?>
                        <?= Html::submitButton('Update', [
                            'class' => 'btn btn-primary pull-right',
                            'name'  => 'action',
                            'value' => 'Update'
                        ]); ?>
                    <?php endif; ?>

                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['blog/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model'         => $model,
                        'form'          => $form,
                        'categories'    => $categories
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
