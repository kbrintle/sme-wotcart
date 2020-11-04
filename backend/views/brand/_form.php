<?php

use frontend\components\Assets;
use yii\helpers\Html;
use common\components\helpers\FormHelper;
use backend\components\CurrentUser;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogBrand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-brand-form">

    <div class="brand-logos">
<!--        <div class="row form-group brand-logos">-->
<!--            <div class="col-xs-6">-->
<!--                --><?php //if( $model->logo_color ): ?>
<!--                    <label class="control-label">Color Logo</label>-->
<!--                    --><?php //echo Html::img(Assets::mediaResource($model->logo_color), [
//                        'alt'   => $model->name,
//                        'class' => 'img-responsive',
//                        'style' => 'width: 100px;'
//                    ]);?>
<!--                --><?php //endif; ?>
<!--            </div>-->
<!--            <div class="col-xs-6">-->
<!--                --><?php //echo $form->field($model, 'logo_color')->fileInput(); ?>
<!--            </div>-->
<!--        </div>-->
    </div>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php echo $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one'])->label('Active'); ?>


</div>