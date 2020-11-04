<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .datetimepicker table {
        margin-left: auto;
        margin-right: auto;
        border: none;
    }
</style>

<div class="event-form">
    <div class="row clearfix">
        <div class ="col-md-4">
            <div class="form-group">
                <label class="control-label">Event Start Date/Time</label>
                <input type="datetime-local" name="StoreEvent[event_start_date]" value="<?=date("Y-m-d\TH:i", strtotime($model->event_start_date))?>"/>
            </div>
        </div>

        <div class ="col-md-4">
            <div class="form-group">
                <label class="control-label">Event End Date/Time</label>
                <input type="datetime-local" name="StoreEvent[event_end_date]" value="<?=date("Y-m-d\TH:i", strtotime($model->event_end_date))?>"/>
<!--            --><?//= DateTimePicker::widget([
//                'name' => 'StoreEvent[event_end_date]',
//                'value' => date("M-d-Y H:i A", strtotime($model->event_end_date)),
//                'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
//                'convertFormat' => true,
//                'pluginOptions' => [
//                    'autoclose' => true,
//                    'format' => 'M-dd-yyyy HH:ii P'
//
//                ],
//                'options' => ['class'=>'col-md-4']
//            ]); ?>
            </div>
        </div>
    </div>


    <?= $form->field($model, 'slug')->textInput(['maxlength' => 255, 'placeholder' => 'slug']) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => 255, 'placeholder' => 'Event Title']); ?>
    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
        'options' => ['rows' => 10],
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor",
                "insertdatetime media table contextmenu paste image imagetools"
            ],
            'toolbar' => "code | table | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | forecolor backcolor | bullist numlist outdent indent | link image",
            'menubar'  => false,
            'statusbar' => false,
            'relative_urls'             => true,
            'remove_script_host'        => false,
            'convert_urls'              => false,
            'image_title'               => true,
            'automatic_uploads'         => true,
            'images_upload_url'         => Url::to(['event/upload']),
            'images_upload_base_path'   => '/uploads/events',
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
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-push-6">
            <label>Current Featured Image</label>
            <br>
            <?= $model->featured_image_path; ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php if ($model->featured_image_path): ?>
                        <img class="bound" src="/<?= $model->featured_image_path; ?>"/>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-md-pull-6">
            <label>Upload Featured Image</label>
            <?= $form->field($model, 'featured_image')->fileInput()->label(false); ?>
        </div>
        <div class="col-xs-12">
            <?= $form->field($model, 'remove_featured_image')->checkbox(); ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'is_active')->dropDownList(\common\components\helpers\FormHelper::getBooleanValues()); ?>
        </div>
    </div>
</div>


