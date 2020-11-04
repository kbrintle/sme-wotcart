<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\store\StoreBanner;

$this->title = 'Detail Banners';

?>

<style>
    .hover {
        border: 6px solid #EBEDEC;
    }

    .hover:hover {
        cursor: pointer;
        border: 6px dotted #00AB50;
    }

    .detail {
        font-weight: 300;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        height: 100%;
        display: table;
        width: 100%;
    }

    .detail-container {
        display: inline-block;
        width: 1110px;
        height: 200px;
    }

</style>

<div id="save" class="alert alert-fixed">Saved</div>
<div id="bannerIndex" class="container-fluid pad-xs">
    <div class="commercials-index">
        <?php foreach ($categories as $detail): ?>
             <?php if(isset($detail)):?>
                <?= Yii::$app->controller->renderPartial('partials/_detail', ['model' => $detail]); ?>
            <?php endif;?>
        <?php endforeach; ?>
        <div id="detail-containernew" class="detail-container modal-click hover" modal="detail-modal">
            <div class="detail" style="background-color:#fff5;text-align: center;"><br><h2>New</h2></div>
        </div>
    </div>
</div>

<?php foreach ($categories as $detail): ?>
<?php if(isset($detail)):?>
    <?= Yii::$app->controller->renderPartial('partials/_detail-modal', ['model' => $detail]); ?>
    <?php endif;?>
<?php endforeach; ?>

<?= Yii::$app->controller->renderPartial('partials/_new-detail-modal'); ?>
