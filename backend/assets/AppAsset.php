<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '_assets/dist/theme.css',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css',
        'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css'
    ];
    public $js = [
        'https://js.stripe.com/v2/',
        '_assets/dist/sme.js',
        '_assets/src/js/js-webshim/minified/polyfiller.js',
        '_assets/src/lib/font-awesome/js/fontawesome-all.min.js',
        'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
