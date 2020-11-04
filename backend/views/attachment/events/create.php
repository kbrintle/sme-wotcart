<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = 'Create Blog';
//$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$form = ActiveForm::begin([
    'id'         => 'blog-create',
    'options'   => [
        'class'     => 'form',
        'enctype'   => 'multipart/form-data'
    ]
]);
?>
    <div class="container-fluid pad-xs">
        <div class="blog-create">
            <div class="row action-row">
                <div class="col-md-12">
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
