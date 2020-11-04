<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;


AppAsset::register($this);

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo = SettingsSeo::find()->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<?= Yii::$app->controller->renderPartial('//layouts/partials/_head',
    [
        'settingsStore' => $settingsStore,
        'settingsSeo' => $settingsSeo
    ]);
?>

<body ng-app="wot-cart">
<?php $this->beginBody() ?>

<div class="wrap" style="background:white;">

    <!-- Render Header -->
    <?php echo $this->render('partials/_header_clean.php') ?>


    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>


</div>

<?php $this->endBody() ?>

<?php echo ($settingsStore) ? $settingsStore->misc_footer_scripts : ''?>
<!-- Start Netsertive GTM Code -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-BF5B" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-BF5B');</script>
<!-- End Netsertive GTM Code -->
</body>
</html>
<?php $this->endPage() ?>
