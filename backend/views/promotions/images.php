<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Homepage Images';
?>

<div class="container-fluid pad-xs">
    <div class="promotions-image-index">

        <?php $form = ActiveForm::begin([
            'id' => 'promotional_images_form'
        ]); ?>
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right']); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-body">
                        <p>Images are restricted to 960x700 pixel size. If you need some help getting started, <a href="/admin/_assets/src/images/960x700-template.png" target="_blank">here's a template</a>!</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="sortable" class="row form-group">
            <?php for( $i = 1; $i <= 4; $i++): ?>
                <div class="col-xs-12 col-md-3 <?= $i==4 ? 'last' : ''; ?>"
                    ng-init="url_<?= $i; ?>='<?= $model->{"image_$i"."_url"}; ?>'">
                    <div class="panel panel__ui drag-panel">
                        <div class="row form-group">
                            <div class="col-xs-12">
                                <div class="drag_handle">
                                    <i class="material-icons">drag_handle</i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

                                <div id="promotions-upload_<?= $i; ?>" class="promotions-upload">
                                    <i class="material-icons">image</i>
                                    <div class="btn-upload image-library-view" data-toggle="modal" data-target="#image-library" data-order="<?= $i; ?>" style="cursor:pointer;">
                                        <p>Image Library</p>
                                    </div>
                                    <div class="btn-upload" onclick="triggerFileInput(<?= $i; ?>)" style="cursor:pointer;">
                                        <p>Upload File</p>
                                    </div>
                                    <p>Image size 960 x 700 px.</p>
                                </div>

                                <div id="promotions-preview_<?= $i; ?>" class="promotions-preview" style="display:none;cursor:pointer;" onclick="triggerFileInput(<?= $i; ?>)">
                                    <label>Upload Preview</label>
                                    <img id="promotions-preview-image_<?= $i; ?>" src="" style="max-width:100%;"/>
                                </div>

                                <?= $form->field($model, "image_$i")->fileInput([
                                    'class'     => 'file_input hidden',
                                    'onchange'  => "renderPreview(this, $i)"
                                ])->label(false); ?>

                                <a id="promotions-preview-delete_<?= $i; ?>" class="btn btn-warning" onclick="removeImage(<?= $i; ?>)" style="display:none;">Remove Image</a>

                            </div>
                        </div>
                        <div class="row">
                            <?php if($model->{"image_$i"."_title"}): ?>
                            <div class="col-xs-12">
                                <?= $model->{"image_$i"."_title"}; ?>
                            </div>
                            <?php endif; ?>
                            <div class="col-xs-12">
                                <?= $form->field($model, "image_$i"."_link")->textInput([
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Link URL'
                                ])->label(false); ?>
                            </div>
                        </div>
                        <?php if($model->{"image_$i"."_url"}): ?>
                        <div class="row form-group image_preview"
                            ng-class="{'deleted': url_<?= $i; ?>==null}">
                            <div class="col-xs-12">
                                <label>Current Image</label>
                                <img class="bound" src="/uploads/<?= $model->{"image_$i"."_url"}; ?>"/>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-xs-12">
                                <a class="btn btn-warning col-xs-12" href="deactivate-image?order=<?= $i ?>">Deactivate Image</a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?= $form->field($model, "image_$i"."_url")->hiddenInput(['value' => "{{url_$i}}"])->label(false); ?>
                    <?= $form->field($model, "image_$i"."_order")->hiddenInput()->label(false); ?>
                </div>
            <?php endfor; ?>
        </div>
        <?php ActiveForm::end(); ?>

        <div class="modal fade" id="image-library">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Image Library</h4>
                    </div>
                    <div class="modal-body">
                        <p class="image-library-title">Select an Image</p>
                        <div class="row">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#local" role="tab" data-toggle="tab">Local Library</a></li>
                                <li role="presentation"><a href="#global" role="tab" data-toggle="tab">Global Library</a></li>
                            </ul>
                            <br />
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="local">
                                    <?php if( $local_img ): ?>
                                        <?php foreach( $local_img as $image ): ?>
                                            <div class="col-md-4" style="padding: 5px 10px 0px 10px;">
                                                <div class="well text-center">
                                                    <?php if( $image->title ): ?>
                                                        <?= $image->title ?>
                                                    <?php else: ?>
                                                        &nbsp;
                                                    <?php endif; ?>
                                                    <img src="/uploads/<?= $image->image ?>" style="height: 150px;" />
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-md-6">
                                                            <a class="col-xs-12 btn btn-sm btn-primary image-library-use" data-order="<?= $image->order ?>" data-id="<?= $image->id ?>">Use</a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="edit-image?id=<?= $image->id ?>" class="col-xs-12 btn btn-sm btn-default image-library-edit">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-md-12">
                                            <p>You don't have any images in your local library. As you add homepage images, more items will appear here.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="global">
                                    <?php if( $global_img ): ?>
                                        <?php foreach( $global_img as $image ): ?>
                                            <div class="col-md-4" style="padding: 5px 10px 0px 10px;">
                                                <div class="well text-center">
                                                    <img src="/uploads/<?= $image->image ?>" style="height: 150px;" />
                                                    <div class="row" style="padding-top: 10px;">
                                                        <div class="col-md-6">
                                                            <a class="col-xs-12 btn btn-sm btn-primary image-library-use" data-order="<?= $image->order ?>" data-id="<?= $image->id ?>">Use</a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <a href="edit-image?id=<?= $image->id ?>" class="col-xs-12 btn btn-sm btn-default image-library-edit">Edit</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="col-md-12">
                                            <p>No global images are currently available.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function renderPreview(input, index){
                var upload_container        = document.getElementById('promotions-upload_'+index);
                var preview_container       = document.getElementById('promotions-preview_'+index);
                var preview_image           = document.getElementById('promotions-preview-image_'+index);
                var preview_delete_button   = document.getElementById('promotions-preview-delete_'+index);

                if( input.files
                    && input.files[0] ){
                    var reader = new FileReader();

                    reader.onload = function(e){
                        if( upload_container
                            && preview_container
                            && preview_image
                            && preview_delete_button){
                            upload_container.style.display      = 'none';
                            preview_container.style.display     = 'block';
                            preview_delete_button.style.display = 'block';
                            preview_image.src                   = e.target.result;
                        }
                    };

                    reader.readAsDataURL(input.files[0]);
                }else{
                    if( upload_container
                        && preview_container
                        && preview_image
                        && preview_delete_button){
                        upload_container.style.display      = 'block';
                        preview_container.style.display     = 'none';
                        preview_delete_button.style.display = 'none';
                        preview_image.src                   = null;
                    }
                }
            }

            function triggerFileInput(index){
                var file_input = document.getElementById('promoimagesform-image_'+index);
                if( file_input )
                    file_input.onclick ? file_input.onclick() : file_input.click();
            }

            function removeImage(index){
                var file_input              = document.getElementById('promoimagesform-image_'+index);
                var upload_container        = document.getElementById('promotions-upload_'+index);
                var preview_container       = document.getElementById('promotions-preview_'+index);
                var preview_image           = document.getElementById('promotions-preview-image_'+index);
                var preview_delete_button   = document.getElementById('promotions-preview-delete_'+index);

                if( file_input ){
                    upload_container.style.display      = 'block';
                    preview_container.style.display     = 'none';
                    preview_delete_button.style.display = 'none';
                    preview_image.src                   = null;
                    file_input.value                    = null;
                    $('#promotional_images_form').yiiActiveForm('validateAttribute', 'promoimagesform-image_'+index);
                }
            }
        </script>
    </div>

</div>