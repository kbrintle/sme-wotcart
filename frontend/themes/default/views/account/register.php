<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\customer\CustomerAddress;
use common\models\core\Subregions;
use yii\helpers\ArrayHelper;

$this->title = 'Signup';
//$this->params['breadcrumbs'][] = $this->title;
?>
<section class="account content-pad content--pad-clean bg-lightgray">
    <div class="container pad-xs">
        <div class="site-signup">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                    <div class="site--signup-container">
                        <h2>Request An Account</h2>
                        <p>
                            SME, Inc. USA features special wholesale pricing reserved for healthcare professionals. Please complete the application below to receive access to product and pricing information.
                            If you are not a healthcare professional, you still have access to professional-grade products at great prices on our patient-friendly website, Body Relief Depot. Thank you for your interest!
                        </p>
                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                        <?= $form->field($model, 'practitioner_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'ordering_contact_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_position')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_address')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_city')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_state')->dropdownlist(ArrayHelper::map(\common\models\core\CountryRegion::find()->where(['country_id' => 'US'])->all(), 'default_name', 'default_name')); ?>

                        <?= $form->field($model, 'clinic_zip')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_phone')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_fax')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'clinic_email')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'contact_email')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'network_member_list')->textInput(['maxlength' => true])->label("If you are a member of any network, please list.") ?>

                        <?= $form->field($model, 'how_hear')->textInput(['maxlength' => true])->label("How did you hear about us?") ?>

                        <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label("List the top five items in which you're interested in saving money.") ?>
                        <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
                        <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
                        <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>
                        <?= $form->field($model, 'top_five[]')->textInput(['maxlength' => true])->label(false) ?>

                        <div class="col-md-12">
                                <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className(),[ 'widgetOptions' => ['class' => 'col-centered']])->label(false) ?>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-7 col-sm-12 col-centered text-center">
                                <div class="form-group clearfix">
                                    <p>
                                        By clicking ‘Create Account’ you agree to SME’s Website<br>
                                        <a href="/terms">Terms of Service</a> and <a href="/privacy">Privacy Policy</a>
                                        .
                                    </p>
                                    <?= Html::submitButton('Create account', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'signup-button']) ?>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>