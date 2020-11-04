<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\store\StoreBanner;

$this->title = 'Category Banners';

?>

<style>
    .hover {
        border: 6px solid #EBEDEC;
    }

    .hover:hover {
        cursor: pointer;
        border: 6px dotted #00AB50;
    }

    .category {
        font-weight: 300;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        min-height: 100%;
        display: table;
        width: 100%;
    }

    .category-container {
        display: inline-block;
        width: 275px;
        height: 649px;
    }

</style>

<div id="save" class="alert alert-fixed">Saved</div>
<div id="bannerIndex" class="container-fluid pad-xs">
    <div class="commercials-index">
        <?php foreach ($categories as $category): ?>
             <?php if(isset($category)):?>
                <?= Yii::$app->controller->renderPartial('partials/_category', ['model' => $category]); ?>
            <?php endif;?>
        <?php endforeach; ?>
        <div id="category-containernew" class="category-container modal-click hover" modal="category-modal">
            <div class="category" style="background-color:#fff5;text-align: center;"><br><h2>New</h2></div>
        </div>
    </div>
</div>

<?php foreach ($categories as $category): ?>
<?php if(isset($category)):?>
    <?= Yii::$app->controller->renderPartial('partials/_category-modal', ['model' => $category]); ?>
    <?php endif;?>
<?php endforeach; ?>

<?= Yii::$app->controller->renderPartial('partials/_new-category-modal'); ?>


