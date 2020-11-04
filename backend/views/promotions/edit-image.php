<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edit Homepage Image';
?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="promotion-new">
            <div class="row">
                <div class="col-md-12">

                    <div class="row action-row">
                        <div class="col-md-12">
                            <?php echo Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']); ?>
                            <?php echo Html::a('Cancel', Url::to(['promotions/images']), ['title' => 'Back', class' => 'btn btn-default btn-spacer pull-right']); ?>
                        </div>
                    </div>

                    <div class="panel panel__ui">
                        <div class="panel-heading">
                            <h4>Homepage Image</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="/uploads/<?= $model->image ?>" style="width: 100%;" />
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <?php echo $form->field($model, 'title')->textInput(); ?>
                                    </div>
                                    <div class="col-md-12">
                                        <?php echo $form->field($model, 'link')->textInput(['maxlength' => true, 'class' => 'link-test-input']); ?>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="<?= $model->link ?>" target="_blank" class="btn btn-secondary link-test">Link Test</a>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="<?= Url::to(["/promotions/delete-image?id=$model->id"]) ?>" class="btn btn-danger delete-promo-image">Delete Image</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>