<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput([
                'maxlength' => true,
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'url_key')->textInput([
                'maxlength' => true,
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'template')->dropDownList(
                $model->templates,
                [
                    'class' => 'form-control'
                ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                'options' => ['rows' => 10],
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste image imagetools"
                    ],
                    'toolbar' => "code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    'menubar'                   => false,
                    //'statusbar'                 => false,
                    'relative_urls'             => true,
                    'remove_script_host'        => false,
                    'convert_urls'              => false,
                    'image_title'               => true,
                    'automatic_uploads'         => true,
                    'branding' => false,
                    'images_upload_url'         => Url::to(['cms/upload']),
                    'images_upload_base_path'   => '/uploads/cms',
                    'file_picker_types'         => 'image',
                    'extended_valid_elements'   => "script[src|type|language]",
                    'file_picker_callback'      => new yii\web\JsExpression("function(cb, value, meta){                    
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        
                        input.onchange = function(){
                            var file = this.files[0];
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var blobInfo = blobCache.create(id, file);
                            blobCache.add(blobInfo);
                            
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                        
                        input.click();
                    }")
                ]
            ]);?>
            <iframe id="form_target" name="form_target" style="display:none">
                <form id="my_form" data-action="/upload/" target="form_target" method="post" enctype="multipart/form-data"
                      style="width:0px;height:0;overflow:hidden">
                    <input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
                </form>
            </iframe>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'meta_description')->textarea([
                'rows' => 2,
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'meta_keywords')->textarea([
                'rows' => 2,
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'is_active')->checkbox() ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>