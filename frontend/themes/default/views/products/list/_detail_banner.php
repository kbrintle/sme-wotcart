<?php

use common\models\store\StoreBanner;
use common\components\CurrentStore;

$categories1 = (array)StoreBanner::getBannerByPageLocation("detail", 0, true); //get all store
$categories2 = (array)StoreBanner::getBannerByPageLocation("detail", CurrentStore::getStoreId(), true);
$categories = array_merge($categories1, $categories2);
if (isset($categories[0])) {
    $category = (object)$categories{array_rand($categories)};
}
?>
<?php if (isset($category)): ?>
    <?php $store = CurrentStore::getStore(); ?>
    <br><br>
    <?php if (isset($category->button_url)): ?>
        <section class="pad-top-sm pad-btm-sm">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="marketing-banner">
                            <div class="big-shop-now-section">
                                <a href="<?= \app\components\StoreUrl::to($category->button_url) ?>">
                                    <div class="product-detail" style="height: 200px; background-size: contain;
                                            background-repeat: no-repeat;
                                            background-position: center; background-image: url('<?= $category->image ?>');">
                                    </div>
                                </a>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>