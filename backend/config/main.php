<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => "SME",
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'on beforeRequest' => function () {
        // Intercept incoming requests and reroute them through
        // the ActionRoute function of the backend StoreController.
        // If necessary, this will ensure that a current store has been
        // set, else, if Superadmin, default to the 'All' alias
        Yii::$app->runAction('store/route');
    },
    'bootstrap' => ['log'],
    'modules' => [
        'imagemanager' => [
            'class' => 'noam148\imagemanager\Module',
            'canUploadImage' => true,
            'canRemoveImage' => true

        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'] // adjust this to your needs
        ],
        'gridview' => ['class' => 'kartik\grid\Module']
    ],
    'aliases' => [
        '@assets' => 'themes/default/_assets/src',
    ],
    'homeUrl' => '/admin',
    'components' => [
        'request' => [
            'baseUrl' => '/admin',
            'csrfParam' => '_csrf-backend',
            'csrfCookie' => [
                'httpOnly' => true,
                'path' => '/admin',
            ],
            'enableCsrfValidation' => false
        ],
        'user' => [
            'identityClass'     => 'common\models\core\Admin',
            'enableAutoLogin'   => true,
            'identityCookie' => [
                'name'      => '_identity-backend',
                'path'      => '/admin',
                'httpOnly'  => true,

            ],
        ],
        'imagemanager' => [
            'class' => 'noam148\imagemanager\components\ImageManagerGetPath',
            'mediaPath' => '/home/brd/sme.wotcart.wideopentech.com/frontend/web/uploads/products',
            //'cachePath' => 'uploads/cache',
            'useFilename' => true,
            'absoluteUrl' => false
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'cookieParams' => [
                'path' => '/admin',
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
        'urlManager' => [
            'rules' => array(
                //'admin/<controller:\w+>' => '<controller>/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                //'catalog/<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                //'catalog/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ),
        ],
    ],
    'params' => $params,
];
