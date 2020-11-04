<?php

use yii\helpers\Html;
use common\models\store\StoreBanner;
use common\components\CurrentStore;
use app\components\StoreUrl;

$mastHead = StoreBanner::getBannerByPageLocation("masthead", CurrentStore::getStoreId());
?>
<section class="hero hero-slider">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active" style="background-image: url('<?=$mastHead->image?>');">
                <div class="carousel-caption">
                    <div class="carousel--caption-inner">
                        <h4><?=$mastHead->title ?></h4>
                        <h1><?=$mastHead->sub_title ?></h1>
                        <p><?=$mastHead->content ?></p>
                        <?php echo Html::a($mastHead->button_text, StoreUrl::to($mastHead->button_url), ['class' => 'btn btn-primary btn-xl']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
