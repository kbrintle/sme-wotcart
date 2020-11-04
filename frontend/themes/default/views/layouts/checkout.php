<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\ActiveForm;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\components\CurrentStore;
use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use common\models\core\CoreConfig;

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo = SettingsSeo::find()->one();

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <?= Yii::$app->controller->renderPartial('//layouts/partials/_head',
            [
                'settingsStore' => $settingsStore,
                'settingsSeo' => $settingsSeo
            ]);
        ?>
    </head>
    <body ng-app="wot-cart">
        <?php $this->beginBody() ?>
        <div class="wrap checkout-layout">
            <!-- Render Header -->
            <?php echo $this->render('partials/_header_clean.php') ?>

            <div class="content-pad">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?= Alert::widget() ?>
                        </div>
                    </div>
                </div>
                <?= $content ?>
            </div>

        </div>
        <?php $this->endBody() ?>
        <!-- Start Netsertive GTM Code -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-BF5B" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-BF5B');</script>
        <!-- End Netsertive GTM Code -->
    </body>
</html>
<?php $this->endPage() ?>
