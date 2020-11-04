<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Top Nav Banner';

?>
<section class="" ng-controller="SettingsFormController">

    <div class="container-fluid customer-create pad-xs">
        <div class="row action-row">
            <div class="col-md-12">

            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-body">

                <div class="settings-store-form">
                    <?php $form = ActiveForm::begin(['id' => 'SettingsStore']); ?>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
                    <div class="pad-top">
                        <h4>Top Nav Banner</h4>
                        <?= $form->field($model, 'banner_type')->dropdownList(['sale' => 'Sale', 'new' => 'New'], ['prompt' => 'None']) ?>
                        <?= $form->field($model, 'banner_text')->textInput() ?>
                        <?= $form->field($model, 'banner_url')->textInput()->label('Banner URL <small>(Example: https://www.smeincusa.com/sme/product-url)</small>') ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>