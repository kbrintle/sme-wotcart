<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create CMS Page';
?>

<?php
$form = ActiveForm::begin([
    'id'         => 'cms_page-create',
    'options'   => [
        'class'     => 'form',
        'enctype'   => 'multipart/form-data'
    ]
]);
?>
<section class="">
    <div class="container-fluid customer-create pad-xs">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary pull-right' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="panel panel__ui">
            <div class="panel-heading">
                <h4>New CMS Page</h4>
            </div>
            <div class="panel-body">

                <?= $this->render('_errors', [
                    'errors' => $model->errors,
                ]) ?>

                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
        </div>
    </div>
</section>
<?php ActiveForm::end(); ?>