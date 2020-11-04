<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;

?>
<section class="account">
    <div class="container-fluid">
        <div class="row top-margin-large">
            <div class="col-lg-3 col-md-4 bg-white">
                <?php echo $this->render('_nav.php') ?>
            </div>
            <div class="col-lg-9 col-md-8 bg-lightGray">
                <div class="col-md-12 buffer-around buffer-around-2">
                    <div class="row">
                        <div class="col-md-12 top-buffer-small bottom-buffer-small">
                            <div class="col-md-12 text-left">
                                <h3 class="color-darkGray">
                                    Newsletter Subscriptions
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel__ui row buffer-around panel-min-height">
                        <div class="buffer-padding ">
                            <div class="form-group buffer-padding">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="lighter space">
                                                    You are currently subscribed to <?php echo Yii::$app()->name ?> Newsletter
                                                </p>
                                            </div>
                                            <div class="col-md-12 top-buffer-big">
                                                <a href="">
                                                    <p class="color-primaryBlue">
                                                        Unsubscribe
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
