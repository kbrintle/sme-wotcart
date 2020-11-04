<?php

use yii\widgets\ActiveForm;

?>
<div class="modal fade" id="category-modal<?= $model->id ?>">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Category Banner</h4>
            </div>
            <div class="modal-body">
                <?php
                $model->page_location = "category";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => $model->page_location.$model->id]]);
                ?>
                <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'button_url')->textInput()->label('Link') ?>
                <?= $form->field($model, 'image')->fileInput()->label('Banner Background Image') ?>
            </div>
            <div class="modal-footer">
                <button id="category-delete" page-location="category" banner-id="<?=$model->id ?>" type="button"
                        class="banner-action btn btn-danger pull-left" href="/admin/banner/ajax-delete" data-dismiss="modal">Delete
                </button>
                <button id="category-save" page-location="category" banner-id="<?=$model->id ?>" type="button"
                        class="banner-action btn btn-primary category-save" href="/admin/banner/ajax-create" data-dismiss="modal">Save
                </button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
