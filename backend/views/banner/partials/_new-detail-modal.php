<?php

use yii\widgets\ActiveForm;
use common\models\store\StoreBanner;

?>
<div class="modal fade" id="detail-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Detail Banner</h4>
            </div>
            <div class="modal-body">
                <?php
                $detail = new StoreBanner;
                $detail->page_location = "detail";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => $detail->page_location."new"]]);
                ?>
                <?= $form->field($detail, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($detail, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($detail, 'button_url')->textInput()->label('Link') ?>
                <?= $form->field($detail, 'image')->fileInput()->label('Banner Background Image') ?>
            </div>
            <div class="modal-footer">
                <button id="detail-save" page-location="<?= $detail->page_location ?>" banner-id="new" type="button"
                        class="banner-action btn btn-primary detail-save"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Create
                </button>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
