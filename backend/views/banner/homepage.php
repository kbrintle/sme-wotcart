<?php

use yii\widgets\ActiveForm;
use common\components\CurrentStore;

$this->title = 'Home Page Banners';
?>
<style>

    .masthead {
        font-family: "Work Sans", sans-serif;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        min-height: 350px;
    }

    .carousel-caption {
        position: relative;
        padding: 30px;
        text-align: left;
        text-shadow: none;
        background: rgba(51, 66, 58, 0.75);
        width: 30%;
        right: 15%;
        top: 50px;
        left: 15%;
    }

    .carousel-caption-inner p {
        font-family: "Source Sans Pro", sans-serif;
        font-size: 14px;
        color: #FFFFFF;
        line-height: 20px;
        letter-spacing: 2px;
        margin: 0 0 10px;
    }

    h4 {
        font-style: normal;
        font-weight: 400;
        margin: 0;
        font-size: 16px;
        text-transform: uppercase;
        color: #00AB50;
    }

    h1 {
        color: #FFFFFF;
        font-size: 25px;
        font-family: 'Work Sans', sans-serif;
        font-style: normal;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    #bannerIndex .btn.btn-primary {
        background: #00AB50;
        border: 1px solid #00AB50;
    }

    #bannerIndex .btn.btn-primary:active {
        background: #00AB50;
        border: 1px solid #00AB50;
    }

    #bannerIndex .btn.btn-primary:hover {
        background: #00AB50;
        border: 1px solid #00AB50;
    }

    #bannerIndex .btn.btn-primary:focus {
        background: #00AB50;
        border: 1px solid #00AB50;
    }

    .carousel-caption .btn {
        text-shadow: none;
    }

    #bannerIndex .btn {
        border-radius: 0;
        color: #FFFFFF;
        font-size: 12px;
        line-height: 1;
        padding: 12px 15px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
    }

    .carousel-indicators {
        padding: 30px 0;
        position: relative;
        bottom: 0;
    }

    .carousel-indicators li {
        width: 44px;
        height: 4px;
        background: #FFFFFF;
        border: none;
        border-radius: 0;
        display: inline-block;
        margin: 1px;
        text-indent: -999px;
        cursor: pointer;
    }

    .carousel-indicators li.active {
        width: 44px;
        height: 4px;
        background: #00AB50;
    }

    .pad-btm {
        padding-bottom: 30px;
    }

    .col-md-6 {
        width: 50%;
    }

    .small-shop-now-section .shop-now {
        font-weight: 300;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        min-height: 328px;
        display: table;
        width: 100%;
    }

    .small-shop-now-section .shop-now .shop-now-center {
        display: table-cell;
        vertical-align: middle;
        position: relative;
        top: 50%;
        text-align: center;
    }

    .small-shop-now-section .shop-now .shop-now-content h5 {
        color: white;
        margin: 0 0 10px;
        font-size: 12px;
        font-weight: 300;
        letter-spacing: 1px;
    }

    h2 {

        display: block;
        margin: 0;
        font-size: 30px;
        font-family: 'Work Sans', sans-serif;
        font-style: normal;
        font-weight: 300;
    }

    .text-white {
        color: #FFFFFF;
    }

    #bannerIndex .btn.btn-secondary {
        color: #FFFFFF;
        background: transparent;
        border: 1px solid #FFFFFF;
    }

    #bannerIndex .btn.btn-secondary:active {
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        color: #33423A;
    }

    #bannerIndex .btn.btn-secondary:hover {
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        color: #33423A;
    }

    #bannerIndex .btn.btn-secondary:focus {
        background: #FFFFFF;
        border: 1px solid #FFFFFF;
        color: #33423A;
    }

    .big-shop-now-section .shop-now {
        font-weight: 300;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        min-height: 328px;
        display: table;
        width: 100%;
    }

    .big-shop-now-section .shop-now .shop-now-center {
        display: table-cell;
        vertical-align: middle;
        position: relative;
        top: 50%;
        text-align: center;
    }

    .big-shop-now-section .shop-now .shop-now-content h5 {
        color: white;
        margin: 0 0 10px;
        font-size: 12px;
        font-weight: 300;
        letter-spacing: 1px;
    }

    .modal-backdrop.in {
        opacity: 0.35;
    }

    .hover {
        border: 6px solid #EBEDEC;
    }

    .hover:hover {
        cursor: pointer;
        border: 6px dotted #00AB50;
    }

</style>

<div id="save" class="alert alert-fixed">Saved</div>
<div id="bannerIndex" class="container-fluid pad-xs">
    <div class="commercials-index">
        <div id="masthead-container" modal="masthead-modal" class="modal-click hover">
            <?= Yii::$app->controller->renderPartial('partials/_masthead', ['model' => $mastHead]); ?>
        </div>
        <div class="pad-btm">
            <div id="left-right-shop-now" class="container">
                <div id="leftshop-container" modal="leftshop-modal" class="small-shop-now-section col-md-6 pad-right hover modal-click">
                    <?php echo Yii::$app->controller->renderPartial('partials/_leftshop',
                    ['model' => $leftShop]); ?></div>
                <div id="rightshop-container" modal="rightshop-modal" class="small-shop-now-section col-md-6 pad-left hover modal-click">
                    <?php echo Yii::$app->controller->renderPartial('partials/_rightshop',
                    ['model' => $rightShop]); ?></div>
            </div>
        </div>
        <div id="bigshop-container" modal="bigshop-modal" class="container hover modal-click">
            <?php echo Yii::$app->controller->renderPartial('partials/_bigshop',
            ['model' => $bigShop]); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="masthead-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit MastHead</h4>
            </div>
            <div class="modal-body">
                <?php
                $mastHead->page_location = "masthead";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=> $mastHead->page_location]]);
                ?>
                <?= $form->field($mastHead, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($mastHead, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($mastHead, 'title') ?>
                <?= $form->field($mastHead, 'sub_title') ?>
                <?= $form->field($mastHead, 'content') ?>
                <?= $form->field($mastHead, 'button_text')->label('Button Text') ?>
                <?= $form->field($mastHead, 'button_url')->textInput() ?>
                <?= $form->field($mastHead, 'image')->fileInput()->label('Banner Background Image') ?>
                <br><br>
                <?=$mastHead->image?>
            </div>
            <div class="modal-footer">

                <button page-location="<?=$mastHead->page_location?>" banner-id="" type="button" class="
                <?= ($mastHead->store_id != CurrentStore::getStoreId()) ? "hidden": "";?>
                pull-left banner-action btn btn-danger"
                        href="/admin/banner/ajax-delete" data-dismiss="modal">Delete</button>

                <button page-location="<?=$mastHead->page_location?>" banner-id="" type="button" class="banner-action btn btn-primary"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Save</button>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="leftshop-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Left Shop Now</h4>
            </div>
            <div class="modal-body">
                <?php
                $leftShop->page_location = "leftshop";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=> $leftShop->page_location]]);
                ?>
                <?= $form->field($leftShop, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($leftShop, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($leftShop, 'title') ?>
                <?= $form->field($leftShop, 'sub_title') ?>
                <?= $form->field($leftShop, 'button_text')->label('Button Text') ?>
                <?= $form->field($leftShop, 'button_url')->textInput() ?>
                <?= $form->field($leftShop, 'image')->fileInput()->label('Banner Background Image') ?>
                <br><br>
                <?=$leftShop->image?>
            </div>
            <div class="modal-footer">

                <button page-location="<?=$leftShop->page_location?>" banner-id="" type="button" class="
                   <?= ($mastHead->store_id != CurrentStore::getStoreId()) ? "hidden": "";?>
                pull-left banner-action btn btn-danger"
                        href="/admin/banner/ajax-delete" data-dismiss="modal">Delete</button>

                <button page-location="<?=$leftShop->page_location?>" banner-id="" type="button" class="banner-action btn btn-primary"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Save
                </button>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rightshop-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Right Shop Now</h4>
            </div>
            <div class="modal-body">
                <?php
                $rightShop->page_location = "rightshop";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=> $rightShop->page_location]]);
                ?>
                <?= $form->field($rightShop, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($rightShop, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($rightShop, 'title') ?>
                <?= $form->field($rightShop, 'sub_title') ?>
                <?= $form->field($rightShop, 'button_text')->label('Button Text') ?>
                <?= $form->field($rightShop, 'button_url')->textInput() ?>
                <?= $form->field($rightShop, 'image')->fileInput()->label('Banner Background Image') ?>
                <br><br>
                <?=$rightShop->image?>

            </div>
            <div class="modal-footer">

                <button page-location="<?=$rightShop->page_location?>" banner-id="" type="button" class="
                 <?= ($mastHead->store_id != CurrentStore::getStoreId()) ? "hidden": "";?>
                pull-left banner-action btn btn-danger"
                        href="/admin/banner/ajax-delete" data-dismiss="modal">Delete</button>

                <button page-location="<?=$rightShop->page_location?>" banner-id="" type="button" class="banner-action btn btn-primary"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Save</button>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="bigshop-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Large Shop Now</h4>
            </div>
            <div class="modal-body">
                <?php
                $bigShop->page_location = "bigshop";
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=> $bigShop->page_location]]);
                ?>
                <?= $form->field($bigShop, 'id')->hiddenInput()->label(false); ?>
                <?= $form->field($bigShop, 'page_location')->hiddenInput()->label(false); ?>
                <?= $form->field($bigShop, 'title') ?>
                <?= $form->field($bigShop, 'sub_title') ?>
                <?= $form->field($bigShop, 'button_text')->label('Button Text') ?>
                <?= $form->field($bigShop, 'button_url')->textInput() ?>
                <?= $form->field($bigShop, 'image')->fileInput()->label('Banner Background Image') ?>
                <br><br>
                <?=$bigShop->image?>

            </div>
            <div class="modal-footer">
                <button page-location="<?=$bigShop->page_location?>" banner-id="" type="button" class="
                 <?= ($mastHead->store_id != CurrentStore::getStoreId()) ? "hidden": "";?>
                pull-left banner-action btn btn-danger"
                        href="/admin/banner/ajax-delete" data-dismiss="modal">Delete</button>
                <button page-location="<?=$bigShop->page_location?>" banner-id="" type="button" class="banner-action btn btn-primary"
                        href="/admin/banner/ajax-create" data-dismiss="modal">Save</button>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>


