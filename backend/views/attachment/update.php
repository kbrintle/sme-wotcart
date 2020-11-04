<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\Catalogattachment */

$this->title = "Update Attachment: $model->title";
?>

<div id="attachments" class="container-fluid pad-xs">
    <div class="attachment-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?php echo Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            </div>
        </div>
        <div class="panel panel__ui">
            <!--div class="panel-heading">
                    <h4><? /*= 'Attachment: '.$model->title; */ ?></h4>
                </div>-->
            <div class="panel-body">
                <?= $this->render('_form', [
                    'isUpdate' => $isUpdate,
                    'model' => $model,
                    'attachmentProducts' => $attachmentProducts,
                    //'form' => $form,
                    'update' => true
                ]) ?>
            </div>
        </div>
    </div>
</div>
