<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;
use common\components\CurrentStore;
use common\models\core\Store;
use \yii\helpers\ArrayHelper;
use common\models\sales\SalesRepresentative;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="save" class="alert alert-fixed">Saved</div>
<div class="customer-form">
    <div class="col-md-12">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general" role="tab" data-toggle="tab">
                    General
                </a></li>
            <?php if (isset($isUpdate)): ?>
                <li role="presentation"><a href="#addresses" role="tab" data-toggle="tab">Addresses</a></li>
                <li role="presentation"><a href="#activity" role="tab" data-toggle="tab">Account Activity</a></li>
                <li role="presentation"><a href="#orderhistory" role="tab" data-toggle="tab">Order History</a></li>
                <li role="presentation"><a href="#rewardpoints" role="tab" data-toggle="tab">Reward Points</a></li>
                <li role="presentation"><a href="#currentcart" role="tab" data-toggle="tab">Current Cart</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <br> <br> <br> <br> <br>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="general">
            <?php $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form',
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'clinic_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <?= $form->field($model, 'store_id')->dropdownList(Store::getStoreList(), ['prompt' => 'Select one'])->label("Associate to Store"); ?>
            <?= $form->field($model, 'sales_rep')->dropdownList(ArrayHelper::map(SalesRepresentative::find()->all(), 'id', 'initials'), ['prompt' => 'Select one'])->label("Sales Representative"); ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt' => 'Select one']); ?>
                </div>
            </div>
            <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
            <?php ActiveForm::end(); ?>
        </div>

        <?php if (isset($isUpdate)): ?>
            <div role="tabpanel" class="tab-pane" id="addresses" user-id="<?= $model->id ?>">
                <?= Yii::$app->controller->renderPartial('partials/_addresses',
                    ['model' => $model]); ?>
            </div>

            <div role="tabpanel" class="tab-pane" id="activity" user-id="<?= $model->id ?>">
                <?= Yii::$app->controller->renderPartial('partials/_activity',
                    ['model' => $model]); ?>
            </div>

            <div role="tabpanel" class="tab-pane" id="orderhistory" user-id="<?= $model->id ?>">
            </div>
            <div role="tabpanel" class="tab-pane" id="rewardpoints" user-id="<?= $model->id ?>">
                <?= Yii::$app->controller->renderPartial('partials/_reward_points',
                    ['model' => $model]); ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="currentcart" user-id="<?= $model->id ?>">
                <?= Yii::$app->controller->renderPartial('partials/_current_cart',
                    ['model' => $model]); ?>
            </div>
        <?php endif; ?>

    </div>
</div>