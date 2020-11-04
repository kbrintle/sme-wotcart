<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Buy X Get Y';
?>
<div class="container-fluid pad-xs">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row action-row">
        <div class="col-md-12 text-right">
            <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['promotions/']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            <?= Html::submitButton('Save', ['name' => 'save', 'class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="discount-index">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-body">
                        <div class="col-md-2 col-md-offset-1"><?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?></div>
                        <div class="col-md-2"><?= $form->field($model, 'x_amount')->textInput(['maxlength' => true, 'placeholder' => 'amount']) ?></div>
                        <div class="col-md-2"><?= $form->field($model, 'x_sku')->textInput(['maxlength' => true, 'placeholder' => 'sku']) ?></div>
                        <div class="col-md-2"><?= $form->field($model, 'y_amount')->textInput(['maxlength' => true, 'placeholder' => 'amount']) ?></div>
                        <div class="col-md-2"><?= $form->field($model, 'y_sku')->textInput(['maxlength' => true, 'placeholder' => 'sku']) ?></div>
                        <div class="form-group field-promotionbuyxgety-y_amount">
                            <label class="control-label" for="promotionbuyxgety-y_amount">free</label>
                        </div>
                        <!--   <div class="col-md-2"><br><? /*= $form->field($model, 'auto_add')->checkbox()  */ ?></div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>