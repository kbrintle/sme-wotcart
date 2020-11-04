<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use common\models\core\LoginForm;
use \common\models\core\CoreConfig;
use yii\helpers\Html;
use frontend\components\UtilitiesHelper;

AppAsset::register($this);

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo   = SettingsSeo::find()->one();


// Fetch login form model
$loginForm     = new LoginForm();
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

                <div class="wrap bg-white">
                    <!-- Render Header -->
                    <?php echo $this->render('partials/_header.php') ?>
                    <div class="page content-pad">
                        <?php if($this->title != 'Home' && $this->title != 'Search'):?>
                        <div class="page-info hidden-print">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <?= Breadcrumbs::widget([
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        ]) ?>

                                            <h2 class="page--info-title"><?= Html::encode($this->title) ?></h2>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?= Alert::widget() ?>
                        <div class="page-content">
                            <?= $content ?>
                        </div>
                    </div>

                </div>
            <div class="hidden-print">
                <!-- Render News Letter -->
                <?php echo $this->render('partials/_newsletter_form.php', []) ?>

                <!-- Render Footer -->
                <?php echo $this->render('partials/_footer.php', ['settingsStore'=>$settingsStore]) ?>

                <!-- Render Zip Modal -->
                <?php echo $this->render('partials/_zipmodal.php', ['settingsStore'=>$settingsStore]) ?>

                <!-- Render Cart Modal -->
                <?php echo $this->render('partials/_cartmodal.php', ['settingsStore'=>$settingsStore]) ?>

                <!-- Render Newsletter Modal -->
                <?php echo $this->render('partials/_newslettermodal.php', []) ?>
            </div>
            <?php $this->endBody() ?>

            <?php echo CoreConfig::getStoreConfig('design/scripts/footer'); ?>

            <!-- Start Netsertive GTM Code -->
            <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-BF5B" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-BF5B');</script>
            <!-- End Netsertive GTM Code -->
        </body>
    </html>
<?php $this->endPage() ?>