<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\promotion\PromotionCode */

$code = $model->isNewRecord ? '' : ": $model->code";
$this->title = "Update Promotion Code $code";
?>
<?php $form = ActiveForm::begin(); ?>
<div id="save" class="alert alert-fixed">Saved</div>
<div class="container-fluid pad-xs">
    <div class="promotion-code-update">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['promotions/codes']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div>
        </div>
        <?php if ($model->type === "Free Product(s)"): ?>
        <div class="panel panel__ui">
            <div class="panel-body">
                <?= $this->render('_free-products-association', [
                    'model' => $model,
                    'form' => $form,
                ]) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php ActiveForm::end(); ?>

