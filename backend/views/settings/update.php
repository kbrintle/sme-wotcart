<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Settings';

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = 'Account Settings';
?>
<section class="" ng-controller="SettingsFormController">

    <div class="container-fluid customer-create pad-xs">
        <div class="row action-row">
            <div class="col-md-12">

            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-heading panel-tab-heading">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#design" aria-controls="design" role="tab" data-toggle="tab">Design</a>
                    </li>
                    <li role="presentation">
                        <a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">SEO</a>
                    </li>
                    <li role="presentation">
                        <a href="#payment" aria-controls="payment" role="tab" data-toggle="tab">Payment</a>
                    </li>
                    <li role="presentation">
                        <a href="#shipping" aria-controls="shipping" role="tab" data-toggle="tab">Shipping</a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="general">
                        <?= $this->render('partials/_general_form', [
                            'model' => $settingsStore,
                        ]) ?>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="design">
                        <?= $this->render('partials/_design_form', [
                            'model' => $settingsStore,
                        ]) ?>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="seo">
                        <?= $this->render('partials/_seo_form', [
                            'model' => $settingsSeo,
                        ]) ?>
                    </div>

                    <div role="tabpanel" class="tab-pane " id="payment">
                        <?= $this->render('partials/_payment_form', [
                            'model' => $settingsPayment,
                        ]) ?>
                    </div>
                    <div role="tabpanel" class="tab-pane " id="shipping">
                        <?= $this->render('partials/_shipping_form', [
                            'model' => $settingsShipping,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>