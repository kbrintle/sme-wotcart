<?php


/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?= $form->field($model, 'title')->textInput(['maxlength' => 72, 'placeholder' => 'Title'])->label(false) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6, 'placeholder' => 'Main Content'])->label(false) ?>

    <?= $form->field($model, 'event_date')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'created_at')->textInput(); ?>


    <?= $form->field($model, 'new_category')->checkbox([
        'onchange' => 'newCategoryTrigger()'
    ])->label(false); ?>

    <div id="old_category">
        <?= $form->field($model, 'category_id')->dropDownList(
            $categories,
            [
                'class'     => 'form-control',
                'prompt'    => 'Select a Category'
            ])->label(false); ?>
    </div>

    <div id="new_category">
        <?= $form->field($model, 'new_category_name')->textInput([
                'class' => 'form-control'
            ])->label("New Category Name"); ?>
    </div>

    <script type="text/javascript">
        function newCategoryTrigger(){
            var checkbox                = document.getElementById('blog-new_category');
            var old_category_container  = document.getElementById('old_category');
            var new_category_container  = document.getElementById('new_category');

            if( checkbox.checked ){
                old_category_container.style.display = 'none';
                new_category_container.style.display = 'block';
            }else{
                old_category_container.style.display = 'block';
                new_category_container.style.display = 'none';
            }
        }
        document.addEventListener("DOMContentLoaded", function(){
            newCategoryTrigger();
        });

    </script>


    <?= $form->field($model, 'short_content')->hiddenInput()->label(false); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-push-6">
            <label>Current Featured Image</label>
            <div class="row">
                <div class="col-xs-12">
                    <?php if( $model->featured_image_path ): ?>
                        <img class="bound" src="/<?= $model->featured_image_path; ?>" />
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-md-pull-6">
            <label>Upload Featured Image</label>
            <?= $form->field($model, 'featured_image')->fileInput()->label(false); ?>
        </div>
        <div class="col-xs-12">
            <?= $form->field($model, 'remove_featured_image')->checkbox()->label(false); ?>
        </div>
    </div>
</div>


