<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account pad-xs">
    <div class="account-information">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="sidebar">
                        <?= $this->render('_nav.php') ?>
                    </div>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel__ui">
                                <div class="panel-heading panel__ui-heading">
                                    <h3 class="panel__ui-heading-ttl">Account Information</h3>
                                </div>
                                <div class="panel-body panel__ui-body">
                                    <?php $form = ActiveForm::begin(['id' => 'info-form']); ?>
                                    <?= $this->render('_partials/_shipping-address.php',
                                        ['form' => $form,
                                            'model' => $model,
                                            'states' => $states]) ?>

                                    <?= $form->field($model, 'same_as_shipping')->checkbox() ?>

                                    <?= $this->render('_partials/_billing-address.php',
                                        ['form' => $form,
                                            'model' => $model,
                                            'states' => $states]) ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right']); ?>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $("#info-form").on('click', '#accountinformationform-same_as_shipping', function () {
            var form = $('#info-billing_address');
            if ($(this).is(':checked') && !form.hasClass("hide")) {
                form.addClass("hide");
                $("#shipping-toggle").html("Address");
            } else {
                form.removeClass("hide");
                $("#shipping-toggle").html("Shipping Address");
            }
        });
    });
</script>
