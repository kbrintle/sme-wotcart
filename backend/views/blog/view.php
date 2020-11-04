<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="blog-view">
            <div class="row action-row">
                <div class="col-md-12">
                    <a href="<?= Url::to(['blog/update', 'id'=>$model->post_id]); ?>" class="btn btn-primary pull-right">Update</a>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['blog/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading clearfix">
                    <h4 class="pull-left"><?= Html::encode($this->title) ?></h4>
                    <?= Html::a('Delete', ['delete', 'id' => $model->post_id], [
                        'class' => 'btn btn-text pull-right',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                <div class="panel-body">
                    <?php
                        $category       = $model->category ? $model->category->title : '';
                        $featured_image = $model->featured_image_path ? "<img src='/$model->featured_image_path' />" : '';
                    ?>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'post_id',
                            'title',
                            'post_content:ntext',
                            'status',
                            'created_time',
                            'update_time',
                            'identifier',
                            'user',
                            'update_user',
                            'meta_keywords:ntext',
                            'meta_description:ntext',
                            'comments',
                            'tags:ntext',
                            [
                                'label' => 'Category',
                                'value' => $category
                            ],
                            [
                                'label' => 'Featured Image',
                                'value' => $featured_image,
                                'format'=> 'html'
                            ],
                            'short_content:ntext',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
