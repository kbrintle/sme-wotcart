<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\core\Store;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Source+Sans+Pro:400,400i,600,700" rel="stylesheet">    <?php $this->head() ?>
</head>
<body class="<?php echo Yii::$app->controller->id."-".Yii::$app->controller->action->id ?>">
<?php $this->beginBody() ?>

<div id="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 main no-pad">

                <div id="page-content">
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
<script src='<?php echo Yii::$app->homeUrl ?>/_assets/src/js/main.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</body>
</html>
<?php $this->endPage() ?>
