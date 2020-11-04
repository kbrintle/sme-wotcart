<?php

use common\models\store\StoreBanner;
use common\components\CurrentStore;

$categories1 = (array)StoreBanner::getBannerByPageLocation("category", 0, true); //get all store
$categories2 = (array)StoreBanner::getBannerByPageLocation("category", CurrentStore::getStoreId(), true);
$categories = array_merge($categories1, $categories2);
if (isset($categories[0])) {
    $category = (object)$categories{array_rand($categories)};
}
?>
<?php if (isset($category)): ?>
    <?php $store = CurrentStore::getStore(); ?>
    <div class="category-banner-section">
        <a href="<?= \app\components\StoreUrl::to($category->button_url) ?>">
            <img src="<?= $category->image ?>" class="img-responsive">
        </a>
    </div>
    <?php endif; ?>