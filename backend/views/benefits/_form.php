<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\store\StoreBenefit */
/* @var $form yii\widgets\ActiveForm */

$icons = \common\models\store\StoreBenefitImage::find()->orderBy(['description'=>'SORT_DESC'])->all();
?>

<div class="store-benefit-form">
    <div class="icon-choices pad-btm clearfix">
            <?php foreach($icons as $icon): ?>
                <div class="col-md-2 ">
                    <i class="material-icons"><?php echo $icon->image_class ?></i>
                    <span><?php echo $icon->description ?></span>
                </div>
            <?php endforeach; ?>
    </div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'image_class')->dropDownList(ArrayHelper::map(\common\models\store\StoreBenefitImage::find()->orderBy(['description'=>'SORT_DESC'])->all(),'id','description')); ?>

    <?= $form->field($model, 'text')->textarea(['id'=>'textInput', 'rows' => 6, 'maxlength'=>'225'])->label("Text - <small>225 Max Characters</small>") ?>
    <span class="countdown"></span>

    <?php ActiveForm::end(); ?>

</div>

