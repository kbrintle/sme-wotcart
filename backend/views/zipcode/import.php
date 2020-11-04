<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
    <div class="store-zip-code-form pad-top">

        <div class="col-md-8 col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">Import</div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin() ?>

                    <?= $form->field($model, 'file')->fileInput()->label('Select File') ?>

                    <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>

                    <?php ActiveForm::end() ?>
                </div>

            </div>
        </div>

        <div class="col-md-8 col-xs-12">

            <div class="panel panel-default">
                <div class="panel-heading">Export</div>
                <div class="panel-body">
                    <?= Html::a( "Export", ['export'],['class' => 'btn btn-primary'] ) ?>
                </div>
            </div>
        </div>

    </div>
