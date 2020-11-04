<?php
namespace xiongchuan\tree;

use yii\web\AssetBundle;
/**
 * SortableTreeAsset bundle
 *
 * @author chuan xiong <xiongchuan86@gmail.com>
 *
 * @since 1.0
 */
class SortableListAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = "@bower" ;
    /**
     * @var array
     */
    public $css = [

    ];
    /**
     * @var array
     */
    public $js = [
        'jquery-sortable-lists/jquery-sortable-lists.js',
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

}