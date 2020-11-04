<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\components\helpers\FormHelper;
use yii\helpers\Url;
?>

<?php $form = ActiveForm::begin(); ?>
<?php if (!$model->id): ?>
    <?= Html::submitButton('Create', ['name' => 'submit', 'value' => "create", 'class' => 'btn btn-primary pull-right']) ?>
    <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['category/index']), ['title' => 'Back', 'class' => 'btn btn-secondary btn-spacer pull-left']); ?>
    <br><br>
<?php endif ?>

<div class="catalog-category-form">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>
    <?= $form->field($model, 'description')->textArea(['maxlength' => true]); ?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <?php if (isset($model->image)): ?>
        <div class="category-header-img"
             style="height: 200px;
                     width: 50%;background-image: url('/uploads/<?= $model->image ?>'); -webkit-background-size: cover;
                     -moz-background-size: cover;
                     -o-background-size: cover;
                     background-size: cover;
                     background-repeat: no-repeat;
                     background-position: 50% 50%;">
        </div>
    <?php endif ?>
    <?= $form->field($model, 'thumbnail')->fileInput() ?>
    <?php if ($model->thumbnail): ?>
        <div class="category-header-img"
             style="height: 200px;
                     width: 50%;background-image: url('/uploads/<?= $model->thumbnail ?>'); -webkit-background-size: cover;
                     -moz-background-size: cover;
                     -o-background-size: cover;
                     background-size: cover;
                     background-repeat: no-repeat;
                     background-position: 50% 50%;">
        </div>
    <?php endif ?>
    <?= $form->field($model, 'banner_image')->fileInput() ?>
    <?php if ($model->banner_image): ?>
        <div class="category-header-img"
             style="height: 200px;
                     width: 50%;background-image: url('/uploads/<?= $model->banner_image ?>'); -webkit-background-size: cover;
                     -moz-background-size: cover;
                     -o-background-size: cover;
                     background-size: cover;
                     background-repeat: no-repeat;
                     background-position: 50% 50%;">
        </div>
    <?php endif ?>
    <?= $form->field($model, 'is_nav')->dropdownList(FormHelper::getBooleanValues(), ['prompt' => 'Select one'])->label('Show on Nav?'); ?>
    <?= $form->field($model, 'is_homepage')->dropdownList(FormHelper::getBooleanValues(), ['prompt' => 'Select one'])->label('Show on Homepage?'); ?>
    <?= $form->field($model, 'is_brand')->dropdownList(FormHelper::getBooleanValues(), ['prompt' => 'Select one'])->label('Is Brand?'); ?>
    <?= $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt' => 'Select one']); ?>
    <?php if ($model->id): ?>
        <?= Html::button('Update', ['id' => 'catUpdate', 'href' => "/admin/category/update/$model->id", 'name' => 'submit', 'value' => "update", 'class' => 'btn btn-primary pull-right']) ?>
        <a href="#" class="btn btn-danger pull-left" data-target="#deleteModal"
           data-toggle="modal">
            Delete
        </a>
    <?php endif ?>
</div>

<!-- Delete List Modal -->
<div class="modal modal__ui fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="createModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you wish to delete "<?= $model->name ?>"?</h5>
            </div>
            <div class="modal-body clearfix text-center">
                <? //= Html::beginForm([StoreUrl::to('favorites/action/' . $list_id)], 'post'); ?>
                <button class="btn btn-success right-small-margin" data-dismiss="modal" name="action">No</button>
                <?= Html::submitButton('Yes', ['name' => "CatalogCategory[delete]", 'value' => $model->id, 'id' => "delete", 'class' => 'btn btn-danger left-small-margin']); ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>