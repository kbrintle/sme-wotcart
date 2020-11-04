<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\StoreUrl;
use yii\bootstrap\ActiveForm;


?>


<!-- Modal -->
<div class="modal modal__ui fade" id="emailStore" tabindex="-1" role="dialog" aria-labelledby="emailStoreLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                'action' => StoreUrl::to('locations/email'),
                'method' => 'post',
                'id' => 'email-store',
                'class' => 'email-store'
            ]); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="emailStoreLabel">For product information or answers to any other questions, please use the form below.</h4>
                <br/>
                <p class="message alert-success hidden">Your email has been sent. We will get back to you as soon as possible</p>
                <p class="message alert-error hidden">There was an issue sending your message.</p>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <?= $form->field($model, 'name')->textInput() ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'email') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Message') ?>
                </div>

            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Send Message', ['class' => 'btn btn-primary', 'name' => 'email-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>