<?php
use yii\helpers\Html;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;



//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo = SettingsSeo::find()->one();


?>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-806882-18"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-806882-18');
    </script>

        <!-- Favicon -->
    <link rel="shortcut icon" href=/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <?php if (YII_ENV_DEV): ?>
        <meta name="robots" content="noindex" />
    <?php endif; ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode( Yii::$app->name  .' - '. $this->title . (isset($settingsSeo->page_title_suffix) && !empty($settingsSeo->page_title_suffix) ? ' - '. $settingsSeo->page_title_suffix : '')) ?></title>
    <?php $this->head() ?>
    

    <?php echo ($settingsStore) ? $settingsStore->misc_header_scripts : '' ?>

</head>