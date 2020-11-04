<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreBenefit */

$this->title = 'Update Store Benefit ';

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="catalog-commercial-update">
            <div class="row action-row">
<!--              -->
            </div>
            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4>Update Benefit Page</h4>
                </div>
                <div class="panel-body">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'form'  => $form
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>