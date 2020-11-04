<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ProductGridAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        '_assets/src/js/angular/controllers/AmericasMattressAJAX.js',
        '_assets/src/js/angular/controllers/ProductTableController.js'
    ];
    public $depends = [

    ];
}
