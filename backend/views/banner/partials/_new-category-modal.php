<?php

use yii\widgets\ActiveForm;
use common\models\store\StoreBanner;

?>

<div class="modal fade" id="category-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Category Banner</h4>
            </div>
            <div class="modal-body">
                <?php
                $category = new StoreBanner();
                $category->page_location = "category";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => $category->page_location."new"]]);
                ?>
                <?= $form->field($category, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($category, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($category, 'button_url')->textInput()->label('Link') ?>
                <?= $form->field($category, 'image')->fileInput()->label('Banner Background Image') ?>
            </div>
            <div class="modal-footer">
                <button id="category-save" page-location="<?= $category->page_location ?>" banner-id="new" type="button"
                        class="banner-action btn btn-primary category-save"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Create
                </button>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>