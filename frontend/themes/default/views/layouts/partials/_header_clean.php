<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use common\components\CurrentStore;
use app\components\StoreUrl;
use yii\widgets\ActiveForm;
use frontend\components\Assets;
use common\models\core\CoreConfig;

AppAsset::register($this);
?>
<header class="header header-clean">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <?php
                NavBar::begin([
                    'brandLabel' => Html::img(Assets::mediaResource(CoreConfig::getStoreConfig('general/design/logo')), ['class'=>'logo__img img-responsive', 'alt'=>Yii::$app->name]),
                    'brandUrl' => StoreUrl::homeurl(),
                    'innerContainerOptions' => [
                        'class' => 'row'
                    ],
                    'options' => [
                        'class' => 'navbar-default',
                    ],
                ]);
      /*          $menuItems = [
                    ['label' => 'Log in', 'url' => [CurrentStore::getStore()->url.'/login'], 'options'=>['class'=>'']],
                ];

                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav pull-right'],
                    'items' => $menuItems,
                ]); */
                NavBar::end();
                ?>
            </div>
        </div>
    </div>
</header>
