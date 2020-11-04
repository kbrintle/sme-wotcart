<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use app\components\StoreUrl;
use frontend\components\Assets;
use common\models\core\CoreConfig;
use common\components\CurrentStore;

AppAsset::register($this);

?>
<footer>
    <div class="container">
        <div class="row">
            <div class="footer-nav">
                <div class="col-sm-3 pad-btm">

                    <h5>SME INC. USA</h5>
                    <span class="footer-address">
                        5949 Carolina Beach Rd.
                        <br/>
                        Wilmington, NC 28412
                    </span>
                    <h5 class="m-t--sm m-b--xs">LOBBY HOURS</h5>
                    <span class="footer-hours">
                        Monday - Friday
                        <br/>
                        8:00 a.m. - 5:00 p.m.
                    </span>
                </div>
                <div class="col-sm-3 pad-btm text-center-mobile">
                    <h5>Company</h5>
                    <ul class="list-unstyled">
                        <li>
                            <?php echo Html::a("About", StoreUrl::to('about-us')); ?>
                        </li>
                        <li>
                            <?php echo Html::a("Contact", StoreUrl::to('contact')); ?>
                        </li>
                        <li>
                            <?php echo Html::a("Returns", StoreUrl::to('returns')); ?>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3 pad-btm text-center-mobile">
                    <h5>SME INC. USA</h5>
                    <ul class="list-unstyled">
                        <li>
                            <?php echo Html::a("Website Assistance", StoreUrl::to('website-assistance')); ?>
                        </li>
                        <li>
                            <?php echo Html::a("Our Guarantee", StoreUrl::to('our-guarantee')); ?>
                        </li>
                        <li>
                            <?php echo Html::a("Terms & Conditions", StoreUrl::to('terms-conditions')); ?>
                        </li>
                        <li>
                            <?php echo Html::a("Privacy Policy", StoreUrl::to('privacy-policy')); ?>
                        </li>
                    </ul>
                </div>

                <?php
                $facebook_url = ($settingsStore && $settingsStore->facebook_url) ? $settingsStore->facebook_url : 'https://www.facebook.com/SMEINCUSA/?fref=ts';
                $twitter_url = ($settingsStore && $settingsStore->twitter_url) ? $settingsStore->twitter_url : 'https://twitter.com/SMEIncUSA';
                ?>

                <div class="col-sm-3 pad-btm text-center-mobile">
                    <?php
                    if ($facebook_url || $twitter_url): ?>
                    <h5>Follow Us</h5>
                    <ul class="social-icons pad-top-sm">
                        <?php if ($facebook_url): ?>
                        <li>
                            <a href="<?php echo $facebook_url ?>" target="_blank">
                                <i class="fab fa-facebook-f fa-2x fb-blue"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($twitter_url): ?>
                            <li>
                                <a href="<?php echo $twitter_url ?>" target="_blank">
                                    <i class="fab fa-twitter fa-2x twitter-blue"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="https://www.linkedin.com/company/sme-inc-usa" target="_blank">
                                <i class="fab fa-linkedin fa-2x"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UCvzkTld-3vsBPfUVDLhRWfQ" target="_blank">
                                <i class="fab fa-youtube fa-2x"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/smeincusa/" target="_blank">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        </li>
                    </ul>
                    <?php endif; ?>
                    <div class="pad-top-sm paybill">
                        <a class="btn btn-primary btn-responsive" href="<?= StoreUrl::to('pay-bill'); ?>" target="_blank">
<!--                            <i class="material-icons paybill-icon">credit_card</i>-->
                            <span class="paybill-text">PAY BILL</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="footer-btm col-sm-12">
                    <p>&copy; <?php echo Yii::$app->name ?> Inc. USA <?php echo date('Y') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="green-bar"></div>
</footer>

