<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
$this->title = 'Update Lead: ' . $model->clinic_name;

$form = ActiveForm::begin([
    'id'         => 'blog-update',
    'options'   => [
        'class'     => 'form',
        'enctype'   => 'multipart/form-data'
    ]
]);
?>
    <div class="container-fluid pad-xs">
        <div class="customer-update">
            <div class="row action-row">
                <div class="col-md-12">
                    <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary pull-right']); ?>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['lead/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>
            <div class="panel panel__ui">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'form'  => $form,
                    ]) ?>
                </div>
            </div>

        </div>
    </div>
<?php ActiveForm::end(); ?>