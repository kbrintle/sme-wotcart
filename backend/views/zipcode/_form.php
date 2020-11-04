<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\core\Store;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $model common\models\StoreZipCode */
/* @var $form yii\widgets\ActiveForm */
?>

<br />

<div class="store-zip-code-form">

    <div class="col-md-8 col-xs-12">

        <div class="panel panel-default">
            <div class="panel-heading">New Zip Code</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <?php if( CurrentStore::getStore() !== null && CurrentStore::getStore()->id == 0): ?>
                    <?= $form->field($model, 'store_id')->dropDownList(ArrayHelper::map(Store::getStore(true), 'id', 'name'), ['prompt'=>'Select A Store', 'options'=>array()]); ?>
                <?php endif; ?>

                <?= $form->field($model, 'zip_code')->textInput(['maxlength' => true]) ?>


                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>