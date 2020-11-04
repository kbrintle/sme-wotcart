<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\customer\CustomerAddress;
use common\models\core\Subregions;
use yii\helpers\ArrayHelper;

$this->title = 'Clinic Spotlight';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="account bg-lightgray">
    <div class="container pad-xs">
        <div class="clinic-signup">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                    <div class="site--signup-container">
<!--                        <h2>Clinic Spotlight</h2>-->
                        <p>
                            We are proud to be launching our newest offering for SME Customers! <br>
                            The new clinic spotlight reward provides clinics with the opportunity to show off their unique facility and team.
                        </p>
                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                        <?= $form->field($model, 'clinic_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_address')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'locations')->textInput(['maxlength' => true])->label('How many locations will this video cover?') ?>

                        <?= $form->field($model, 'response')->textArea(['rows'=>6])->label('Please write a brief response as to why you would like a clinic spotlight.') ?>

                        <?= $form->field($model, 'social')->inline(true)->checkboxList(['facebook'=>'Facebook', 'instagram'=>'Instagram', 'linkedIn'=>'LinkedIn', 'YouTube'=>'Youtube', 'twitter'=>'Twitter', 'other'=>'Other'])->label('Do you currently utilize any social platforms? <small> Check all that apply.</small>') ?>

                        <div id="social-other" class="hidden">
                            <?= $form->field($model, 'social_other')->textInput(['maxlength' => true])->label('Specify the social other') ?>
                        </div>

                        <?= $form->field($model, 'sending')->inline(true)->checkboxList(['clinic website'=>'Clinic Website', 'social media'=>'Social Media', 'email'=>'Email', 'other'=>'Other'])->label('Where will you be placing/sending your video once completed? <small> Check all that apply.</small>') ?>

                        <div id="sending-other" class="hidden">
                            <?= $form->field($model, 'sending_other')->textInput(['maxlength' => true])->label('Specify the other method') ?> ?>
                        </div>
                        <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true]) ?>

                     <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className(),[ 'widgetOptions' => ['class' => '']])->label(false) ?>
                            <?= Html::submitButton('Submit Application', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'signup-button']) ?>
                        </div>
                    </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {

        $(document).on('change', '#clinicspotlightform-social input[value="other"]', function(){
            if($(this).prop('checked')){
                $('#social-other').removeClass('hidden');
            }else{
                $('#social-other').addClass('hidden');
            }
        });

        $(document).on('change', '#clinicspotlightform-sending input[value="other"]', function(){
            if($(this).prop('checked')){
                $('#sending-other').removeClass('hidden');
            }else{
                $('#sending-other').addClass('hidden');
            }
        });


    });

</script>