<?php
use kartik\mpdf\Pdf;
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
        ],
        'pdf' => [
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            // refer settings section for all configuration options
        ],
        'cache'         => [
            'class' => 'yii\redis\Cache',
            'redis'        => [
                'class'    => 'yii\redis\Connection',
                'hostname' => 'localhost',
                'port'     => 6379,
                'database' => 0,
            ],
        ]
    ],
];

