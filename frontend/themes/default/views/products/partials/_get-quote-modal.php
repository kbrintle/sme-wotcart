<?php

use app\components\StoreUrl;
use frontend\models\GetQuoteForm;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

$model = new GetQuoteForm();

?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'id' => 'quote-form',
    'action' => StoreUrl::to('quote/submit')
]); ?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Get a Quote Now</h3>
        </div>
        <div class="modal-body">
            <div class="success hidden">
                <p>
                    Thank you for your request. SME will respond with a formal quote via email in 24 - 48 hours.
                </p>
            </div>
            <div class="fields">
                <?= $form->field($model, 'name')->textInput(); ?>
                <?= $form->field($model, 'clinic')->textInput(); ?>
                <?= $form->field($model, 'email')->textInput(); ?>
                <?= $form->field($model, 'phone')->textInput(); ?>
                <?= $form->field($model, 'product')->hiddenInput(['value'=>$id])->label(false); ?>
                <p>
                    <br>
                    If you have any additional information to offer, please do so below.
                </p>
                <?= $form->field($model, 'notes')->textarea([
                    'rows' => 4,
                    'class' => 'form-control'
                ]) ?>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button " class="close-btn btn btn-default" data-dismiss="modal">Close</button>
            <a href="#" id="quote-submit" class="btn btn-primary">Submit</a>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

