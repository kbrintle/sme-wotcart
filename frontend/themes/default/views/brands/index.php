<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\Assets;
use app\components\StoreUrl;

?>
<section class="bg-lightgray content-pad">
    <div class="container">
        <div class="row pad-top">
            <div class="col-md-8 col-md-offset-2">
                <h2 class="text-center">Our Brands</h2>
            </div>
        </div>
        <div class="row pad-sm">
            <?php foreach($brands as $brand):?>
            <div class="col-md-3">
                <div class="panel panel-brand">
                    <a href="<?php echo StoreUrl::to("mattress/brands/".$brand->slug );?>">
                        <div class="panel-body">
                             <?php echo Html::img(Assets::mediaResource($brand->logo_color), ['alt'=>$brand->name, 'class'=>'img-responsive center-block']);?>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>