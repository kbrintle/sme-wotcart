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
    <?php echo $this->render('partials/_header.php') ?>


    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
    <?= Alert::widget() ?>
    <?= $content ?>


</div>

<?php $this->endBody() ?>

<?php echo ($settingsStore) ? $settingsStore->misc_footer_scripts : ''?>
</body>
</html>
<?php $this->endPage() ?>
