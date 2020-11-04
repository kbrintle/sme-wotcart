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
use backend\components\CurrentUser;
use common\models\core\Admin;
use frontend\components\Assets;
use common\models\customer\Lead;

AppAsset::register($this);
$lead_count = Lead::getNewLeadsCount();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Source+Sans+Pro:400,400i,600,700"
          rel="stylesheet"> <?php $this->head() ?>
</head>
<body class="<?php echo Yii::$app->controller->id . "-" . Yii::$app->controller->action->id ?>"
      ng-app="wot-cart">
<?php $this->beginBody() ?>

<div id="wrapper">
    <header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 col-lg-7">
                    <div class="logo pull-left">
                        <?php echo Html::a(Html::img(Assets::themeResource('logos/sme-logo.svg'), ['alt' => Yii::$app->name, 'class' => 'logo__img img-responsive']), Yii::$app->homeUrl); ?>
                        <!--                        <a href="--><?php //echo Yii::$app->homeUrl ?><!--"><img alt="-->
                        <?php //echo Yii::$app->name; ?><!--" src="-->
                        <?php //echo Yii::$app->homeUrl ?><!--/_assets/src/images/AmMat-Logo-Color.svg" /></a>-->
                    </div>
                </div>
                <div class="col-md-7 col-lg-5 right-nav">
                    <ul class="header-nav-list pull-right">

                        <?php if (CurrentUser::getUserId()): ?>
                            <li class="header-nav-list-item">
                                <div class="user__account">
                                    <div class="dropdown profile-dropdown">
                                        <span class="user__account-profile">
                                             <?= Admin::findIdentity(CurrentUser::getUserId())->getInitials() ?>
                                        </span>
                                        <button class="btn btn-profile-dropdown dropdown-toggle" type="button"
                                                id="profileDropdown" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="true">
                                            <i class="material-icons">expand_more</i>
                                        </button>
                                        <ul class="dropdown-menu pull-right" aria-labelledby="profileDropdown">
                                            <li><a href="/admin/site/logout">Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar no-pad">
                <ul class="nav nav-sidebar">
                    <h3 class="nav-menu-heading">Menu</h3>
                    <?php if (CurrentUser::isAdmin()): ?>
                    <li>
                        <a href="<?= Url::to(['/dashboard']) ?>"> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <?php endif; ?>
                    <li data-toggle="collapse" data-target="#sales" class="collapsed">
                        <a>Sales <span class="arrow"></span></a>
                        <ul class="sub-menu collapse" id="sales">
                            <li>
                                <a href="<?= Url::to(['/orders']) ?>">Orders</a>
                            </li>
                        </ul>
                    </li>
                    <?php if (CurrentUser::isAdmin()): ?>
                    <li data-toggle="collapse" data-target="#catalog" class="collapsed">
                        <a>Catalog <span class="arrow"></span></a>
                        <ul class="sub-menu collapse" id="catalog">
                            <li><a href="<?= Url::to(['/product/new']) ?>">Add New Product</a></li>
                            <li><a href="<?= Url::to(['/product']) ?>">Products</a></li>
                            <?php if (CurrentUser::isAdmin()): ?>
                               <!-- <li><a href="<?/*= Url::to(['/imagemanager']) */?>">Media</a></li>-->
                                <li><a href="<?= Url::to(['/brand']) ?>">Brands</a></li>
                                <li><a href="<?= Url::to(['/category']) ?>">Categories</a></li>
                                <li><a href="<?= Url::to(['/attachment']) ?>">Attachments</a></li>
                                <li><a href="<?= Url::to(['/attributeset']) ?>">Attribute Sets</a></li>
                                <li><a href="<?= Url::to(['/attribute']) ?>">Attributes</a></li>
                                <li><a href="<?= Url::to(['/reviews']) ?>">Reviews</a></li>
                                <?php if (CurrentUser::getUserId() != 12): ?>
                                    <li><a href="<?= Url::to(['/import']) ?>">Import &amp; Export</a></li>
                                    <li><a href="<?= Url::to(['/import/group']) ?>">Group Pricing Import &amp; Export</a></li>
                                    <li><a href="<?= Url::to(['/import/group-tiered-pricing']) ?>">Tiered Pricing Import &amp; Export</a></li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <li><a href="<?= Url::to(['/import/pricing']) ?>">Store Pricing Import &amp; Export</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?= Url::to(['/lead']) ?>"> <span class="nav-label">Leads <?php if($lead_count > 0):?><span class="badge badge-secondary"><?= Lead::getNewLeadsCount() ?></span><?php endif; ?></span></a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/customer']) ?>"> <span class="nav-label">Customers</span></a>
                    </li>

                    <li data-toggle="collapse" data-target="#homepage" class="collapsed">
                        <a>Banner Customizations<span class="arrow"></span></a>
                        <ul class="sub-menu collapse" id="homepage">
                            <li>
                                <a href="<?= Url::to(['/banner/top-nav-banner']) ?>"> <span class="nav-label">Top Nav Banner</span></a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/banner/home-page']) ?>"> <span class="nav-label">Home Page Banners</span></a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/banner/product-category']) ?>"> <span class="nav-label">Product Category Banners</span></a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/banner/product-detail']) ?>"> <span class="nav-label">Product Detail Banners</span></a>
                            </li>
                        </ul>
                    </li>

                        <li data-toggle="collapse" data-target="#promotions" class="collapsed">
                            <a>Promotions <span class="arrow"></span></a>
                            <ul class="sub-menu collapse" id="promotions">
                                <li>
                                    <a href="<?= Url::to(['/promotions']) ?>"> <span class="nav-label">Promotion Scheduler</span></a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/promotions/codes']) ?>"> <span class="nav-label">Discount Codes</span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/cms']) ?>"> <span class="nav-label">CMS</span></a>
                        </li>
                        <li>
                            <a href="<?= Url::to(['/event']) ?>"> <span class="nav-label">Events</span></a>
                        </li>



                    <li data-toggle="collapse" data-target="#stores" class="collapsed">
                        <a>Stores <span class="arrow"></span></a>
                        <ul class="sub-menu collapse" id="stores">
                            <!--                                <li><a href="-->
                            <? //= Url::to(['/location']) ?><!--"> <span class="nav-label">Locations</span></a></li>-->
                            <?php if (CurrentUser::isAdmin()): ?>
                                <li><a href="<?= Url::to(['/store']) ?>">List Stores</a></li>

                                <li><a href="<?= Url::to(['/newsletter']) ?>">Newsletter Subscribers</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                        <li>
                            <a href="<?= Url::to(['/searchanise']) ?>" <span class="nav-label">Searchanise</span></a>
                        </li>

                        <li>
                            <a href="<?= Url::to(['/users']) ?>" <span class="nav-label">Users</span></a>
                        </li>

                    <li>
                        <a href="<?= Url::to(['/settings/update']) ?>"> <span class="nav-label">Configuration</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main no-pad content-pad">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <div id="page-content">
                    <!--Load subheader partial --- See layouts/partials/subheader.php-->
                    <?= Alert::widget() ?>
                    <?= Yii::$app->controller->renderPartial('//layouts/partials/subheader'); ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Yii::$app->controller->renderPartial('//layouts/partials/create-store-modal'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
