<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Settings';

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = 'Homepage Content Settings';
//$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<section class="" ng-controller="SettingsFormController">
    <?php $form = ActiveForm::begin(); ?>

    <div class="container-fluid customer-create pad-xs">
        <div class="row action-row">
            <div class="col-md-12">

            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-heading">
                <h4>Homepage Content</h4>
            </div>
            <div class="panel-body">
                <?= $this->render('partials/_policy_form', [
                    'model' => $settingsStore,
                ]) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</section>