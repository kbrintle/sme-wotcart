<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\Assets;
use common\models\catalog\CatalogCategory;

?>
<div class="category-header">
    <div class="row">
        <div class="col-md-12">
            <div class="category-header-img" style="background-image: url(<?php echo Assets::mediaResource(CatalogCategory::getBanner($category)); ?>); -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: 50% 50%; ">

            </div>
        </div>
    </div>
</div>

