<?php
use app\components\StoreUrl;
use yii\helpers\Html;
use frontend\components\Assets;
?>
<div class="row search_panel search_panel-store_location locations--single">
    <div class="col-xs-12">
        <div class="panel">
            <div class="row">
                <div class="col-sm-4">
                    <div class="locations--store-img">
                        <?= Html::img(Assets::themeResource('stores/store_locations_img.jpg'), ['alt'=>Yii::$app()->name, 'class'=>'img-responsive']);?>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="locations--store-details">
                        <div class="row">

                            <div class="col-sm-7">
                                <h2 class="locations--store-title"><?= Html::encode($model->name); ?></h2>
                                <div class="locations--store-address">
                                    <span class="locations--store-address-line1"><?= Html::encode($model->address); ?></span>
                                    <span class="locations--store-address-line2"><?= Html::encode($model->alt_address); ?></span>
                                    <span class="locations--store-city"><?= Html::encode($model->city); ?>, <?= Html::encode($model->state); ?> <?= Html::encode($model->zipcode); ?></span>
                                </div>
                                <span class="locations--store-phone"><?= Html::encode($model->phone); ?></span>
                                <div class="locations--store-hours">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <?= $this->render('../../locations/_hours', ['location' => $model]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="locations--store-links">
                                    <a href="#" class="email-store btn btn-default btn-xl btn-responsive btn-pad-btm">Email Store</a>
                                    <a href="#" class="directions-store btn btn-default btn-xl btn-responsive btn-pad-btm">Get Directions</a>
                                    <a href="<?php echo StoreUrl::to("locations/detail/".$model->slug);?>" class="view-store btn btn-primary btn-xl btn-responsive">View Details</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>