<?php

use common\components\CurrentStore;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
//    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => "SME",
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'on beforeRequest' => function () {
        // Intercept incoming requests and reroute them through
        // the ActionRoute function of the frontend StoreController.
        // If necessary, this will inject the current store URL into
        // the request URL, then/else complete serving the request.
        Yii::$app->runAction('store/route', [
            'request' => Yii::$app->request,
        ]);
    },
//    'on beforeAction' => function(){
//        // Intercept incoming requests and reroute them through
//        // the ActionRoute function of the frontend StoreController.
//        // If necessary, this will inject the current store URL into
//        // the request URL, then/else complete serving the request.
//        Yii::$app->runAction('store/redirects', [
//            'request' => Yii::$app->request,
//        ]);
//    },
    'aliases' => [
        '@theme' => '@app/themes/default',
        '@assets' => 'themes/default/_assets/src',
    ],
    'homeUrl' => '/',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend'
        ],
        'cart' => [
            'class' => 'common\components\wotcart\Cart'
        ],
        'user' => [
            'identityClass' => 'common\models\customer\Customer',
            'enableAutoLogin' => false,
            'authTimeout' => 60 * 60,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-frontend',
            'class' => 'yii\web\Session',
            'cookieParams' => ['lifetime' => 365 * 24 * 60 * 60],
            'timeout' => 365 * 24 * 60 * 60, //session expire
            'useCookies' => true,
        ],
        'view' => [
            'theme' => [
                'basePath' => '@theme/views',
                'baseUrl' => '@theme/views',
                'pathMap' => [
                    '@app/views' => '@theme/views',
                ],
            ],
        ],
        'currentStore' => [
            'class' => 'common\components\CurrentStore'
        ],
        'assetManager' => [
            'appendTimestamp' => false,
            'class' => 'yii\web\AssetManager',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position' => \yii\web\View::POS_HEAD],
                    'js' => [
                        'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ]
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6Lc02nEUAAAAAFk2Bg0PUpHp0ldfqnEc_33ylITK',
            'secret' => '6Lc02nEUAAAAABWN-IOR_UipOMyNHKMEsyMn_VEy',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                /**
                 * AJAX rules
                 */
                'wizard/all' => 'wizard/all',
                '<store:[\w-]+>/shop/review-submission' => 'shop/review-submission',

                /**
                 * Product Grid url maps
                 *  brand/${brand}
                 *  comfort-level/${comfort-level}
                 *  brand/${brand}/comfort-level/${comfort-level}
                 *
                 */
                '<store:[\w-]+>/products' => 'products/index',
                '<store:[\w-]+>/store-policies' => 'policies',
                '<store:[\w-]+>/specials' => 'specials/index',
                '<store:[\w-]+>/contact' => 'contact/index',
                '<store:[\w-]+>/cart' => 'cart/index',
                '<store:[\w-]+>/about' => 'about/index',
                '<store:[\w-]+>/account' => 'account/index',
                '<store:[\w-]+>/account/order/<id:[\w-]+>' => 'account/order',
                '<store:[\w-]+>/search' => 'search/index',
                '<store:[\w-]+>/search/<q:[\w=]+>' => 'search/index',
                '<store:[\w-]+>/checkout' => 'checkout/index',
                '<store:[\w-]+>/events' => 'events/index',
                '<store:[\w-]+>/events/<slug:[\w-]+>' => 'events/detail',
                '<store:[\w-]+>/account/register-thank-you' => 'account/register-thank-you',
                '<store:[\w-]+>/<url_key:[\w-]+>' => 'cms/view',
                '<store:[\w-]+>/favorites' => 'favorites/index',
                '<store:[\w-]+>/favorites/update/<list_id:[\w-]+>' => 'favorites/update',
                '<store:[\w-]+>/favorites/remove-item/<id:[\w-]+>' => 'favorites/remove-item',
                '<store:[\w-]+>/favorites/remove-list/<id:[\w-]+>' => 'favorites/remove-list',
                '<store:[\w-]+>/shop/category/<category:[\w-]+>' => 'shop/category',
                '<store:[\w-]+>/shop/brand/<brand:[\w-]+>' => 'shop/brand',
                '<store:[\w-]+>' => 'store/index',

                '<store:[\w-]+>/<controller:\w+>' => '<controller>/index',
                '<store:[\w-]+>/shop/products/<slug:[\w-]+>' => '/shop/view',
                '<store:[\w-]+>/shop/<category:[\w-]+>' => '/shop/category',


                '<store:[\w-]+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<store:[\w-]+>/<controller:\w+>/<action:[\w-]+>' => '<controller>/<action>'
            ],
        ],
        'mobileDetect' => [
            'class' => '\skeeks\yii2\mobiledetect\MobileDetect'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mandrillapp.com',
                'username' => '',
                'password' => '',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
    ],

    'params' => $params,
];
