<?php

use app\components\StoreUrl;
use frontend\components\Assets;

?>

<div class="brands-detail">
    <section class="brand-masthead" style="background-image: linear-gradient(rgba(20,20,20, .5), rgba(20,20,20, .5)), url(<?php echo Assets::themeResource($brand->masthead_image);?>)">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7 col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
                    <div class="masthead__content">
                        <h1><?php echo $brand->name ?></h1>
                        <p class="brand-description"><?php echo $brand->text ?></p>
                        <a href="<?php echo StoreUrl::to("/shop/category/mattresses?brands=$brand->name") ?>" class="btn btn-primary btn-xl btn-responsive" href="<?php echo StoreUrl::to('shop/brand/'.$brand->slug) ?>">Shop Brand</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="bg-lightgray pad-btm">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="pad-sm">Brand Features</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-brand-ft">
                        <div class="panel-heading">
                            <?php if($brand->feature1_image): ?>
                                <figure>
                                    <img src="<?php echo Assets::mediaResource($brand->feature1_image) ?>" alt="<?php echo $brand->name ?>" class="img-responsive"/>
                                </figure>
                            <?php endif; ?>
                            <?php if($brand->feature1_video): ?>
                                <div class="embed-video">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <?php echo $brand->feature1_video ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-body">
                            <h3><?php echo $brand->feature1_title ?></h3>
                            <p><?php echo $brand->feature1_text ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-brand-ft">
                        <div class="panel-heading">
                            <?php if($brand->feature2_image): ?>
                                <figure>
                                    <img src="<?php echo Assets::mediaResource($brand->feature2_image) ?>" alt="<?php echo $brand->name ?>" class="img-responsive"/>
                                </figure>
                            <?php endif; ?>
                            <?php if($brand->feature2_video): ?>
                                <div class="embed-video">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <?php echo $brand->feature2_video ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-body">
                            <h3><?php echo $brand->feature2_title ?></h3>
                            <p><?php echo $brand->feature2_text ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-brand-ft">
                        <div class="panel-heading">
                            <?php if($brand->feature3_image): ?>
                                <figure>
                                    <img src="<?php echo Assets::mediaResource($brand->feature3_image) ?>" alt="<?php echo $brand->name ?>" class="img-responsive"/>
                                </figure>
                            <?php endif; ?>
                            <?php if($brand->feature3_video): ?>
                                <div class="embed-video">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <?php echo $brand->feature3_video ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="panel-body">
                            <h3><?php echo $brand->feature3_title ?></h3>
                            <p><?php echo $brand->feature3_text ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
