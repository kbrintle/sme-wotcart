<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'themes/default/_assets/dist/theme.css',
        'themes/default/_assets/src/lib/flexslider/flexslider.css',
        'https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600',
        'https://fonts.googleapis.com/icon?family=Material+Icons'
    ];
    public $js = [
        'themes/default/_assets/dist/sme.js',
        'themes/default/_assets/src/lib/font-awesome/js/fontawesome-all.min.js',
        'themes/default/_assets/src/lib/jquery-zoom/jquery.zoom.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
