<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use common\models\core\CoreConfig;


$this->title = 'Contact';
?>
<section class="content-pad">
    <div class="container">
        <div class="row pad-sm">
            <div class="col-sm-12">
                <div class="site-contact">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Contact</h2>
                            <hr/>
                            <p class="pad-btm">We want to hear from you! Use our contact form below to submit your inquiry, and we’ll make every effort to respond to you within 2 business days.</p>

                            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                            <?= $form->field($model, 'name')->textInput() ?>

                            <?= $form->field($model, 'email') ?>

                            <?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Message') ?>

                            <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className(),[ 'widgetOptions' => ['class' => 'col-centered']])->label(false) ?>
                            <div class="form-group text-center">
                                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'contact-button']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="col-md-5 col-md-offset-1">
                            <div class="panel bg-lightgray">
                                <div class="panel-heading">
                                    <h3>GENERAL CONTACT INFO</h3>
                                    <hr/>
                                    <ul class="st-loc">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="block-content"><p>SME Inc. USA<br>
                                                        5949 Carolina Beach Rd.<br>
                                                        Wilmington, NC 28412</p><p>

                                                    </p><p>PO Box 15209<br>
                                                        Wilmington, NC 28408</p><p>

                                                    </p>
                                                    <p>Lobby Hours:<br>
                                                        Monday - Friday<br>
                                                        8:00 a.m. - 5:00 p.m.<br>
                                                    </p></div>
                                                <div class="block-content">
                                                    <p>Office:  800.538.4675    <br>
                                                        Fax:  800.560.5424     </p>
                                                </div>

                                                <div class="block-content">
                                                    <p>
                                                    <strong>SME Inc. USA Order Inquiries:</strong> <a href="mailto:ordernow@smeincusa.com">ordernow@smeincusa.com</a><br><br>
                                                    <strong>SME Inc. USA Product Quotes:</strong> <a href="mailto:quote@smeincusa.com">quote@smeincusa.com</a><br><br>
                                                    <strong>SME Inc. USA Website Inquiries:</strong> <a href="mailto:kim@smeincusa.com">info@smeincusa.com</a>
                                                    </p>
                                                </div>
                                            </div
                                        </div>
                                    </ul>
                                </div>
                                <div class="panel-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
