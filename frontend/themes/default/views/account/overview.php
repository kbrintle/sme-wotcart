<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;
use common\models\customer\Customer;
use common\models\customer\CustomerAddress;
use common\models\customer\CustomerReward;
use common\models\core\CountryRegion;

use frontend\models\CheckoutForm;

$this->title = $this->title;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="account pad-xs">
    <div class="account-overview">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="sidebar">
                        <?= $this->render('_nav.php') ?>
                    </div>
                </div>
                <div class="col-sm-9 col-md-9">
                    <div class="row">
                        <!--      <div class="col-md-12">
              <div class="panel panel__ui">
                <div class="panel-heading panel__ui-heading clearfix">
                  <h3 class="panel__ui-heading-ttl pull-left">My Orders</h3>
                  <? /*= Html::a('View All', [StoreUrl::to('account/orders')], ['class' => 'pull-right']) */ ?>
                </div>
                <div class="panel-body panel__ui-body">
                  <?php
                        /*                  if ($customer->orders):
                                            foreach ($customer->orders as $order): */ ?>
                      <div class="row">
                        <div class="col-md-4">
                          <a href="<?php /*echo StoreUrl::to("account/order/" . $order->order_id) */ ?>">
                            <?php /*echo $order->order_id; */ ?>
                          </a>
                        </div>
                        <div class="col-md-4">
                          <p>
                            <?php /*echo date('M d, Y g:i a', $order->created_at); */ ?>
                          </p>
                        </div>
                        <div class="col-md-4">
                          <p>
                            <?php /*echo ($order->orderStatus) ? $order->orderStatus->name : 'Status Not Available'; */ ?>
                          </p>
                        </div>
                      </div>
                    <?php /*endforeach; */ ?>
                  <?php /*else: */ ?>
                    <p>
                      There are currently no existing orders.
                    </p>
                  <?php /*endif; */ ?>
                </div>
              </div>
            </div>-->
                        <div class="col-md-6">
                            <div class="panel panel__ui">
                                <div class="panel-heading panel__ui-heading clearfix">
                                    <h3 class="panel__ui-heading-ttl pull-left">Account Information</h3>
                                    <?= Html::a('Edit', StoreUrl::to('account/information'), ['class' => 'pull-right']) ?>
                                </div>
                                <div class="panel-body panel__ui-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pad-btm">
                                                <h4>Name</h4>
                                                <span><?= $customer->getFullName(); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="pad-btm">
                                                <h4>Email</h4>
                                                <span><?= $customer->email; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="pad-btm">
                                                <h4>Password</h4>
                                                <?= Html::a('Change Password', StoreUrl::to("account/update-password")) ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if ($customer->address): ?>
                                                <div class="pad-btm">
                                                    <h4>Default Shipping Address</h4>
                                                    <?php if ($shipping): ?>
                                                        <?php if (isset($shipping->address_1)): ?>
                                                            <span><?= $shipping->address_1 ?></span>
                                                        <?php endif; ?>
                                                        <br>
                                                        <span>
                                                        <?php if (isset($shipping->city)): ?>
                                                            <?= $shipping->city ?>,
                                                        <?php endif; ?>
                                                            <?php if ($region = CountryRegion::getRegionById($shipping->region_id)): ?>
                                                                <?= $region->code ?>
                                                            <?php endif; ?>
                                                            <?php if (isset($shipping->postcode)): ?>
                                                                <?= $shipping->postcode ?>
                                                            <?php endif; ?>
                                                            </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if ($customer->address): ?>
                                                <div class="pad-btm">
                                                    <h4>Default Billing Address</h4>
                                                    <?php if ($billing): ?>
                                                        <?php if (isset($billing->address_1)): ?>
                                                            <span><?= $billing->address_1 ?></span>
                                                        <?php endif; ?>
                                                        <br>
                                                        <span>
                                                        <?php if (isset($billing->city)): ?>
                                                            <?= $billing->city ?>,
                                                        <?php endif; ?>
                                                            <?php if ($region = CountryRegion::getRegionById($billing->region_id)): ?>
                                                                <?= $region->code ?>
                                                            <?php endif; ?>
                                                            <?php if (isset($billing->postcode)): ?>
                                                                <?= $billing->postcode ?>
                                                            <?php endif; ?>
                                                            </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel__ui">
                                <div class="panel-heading panel__ui-heading clearfix">
                                    <h3 class="panel__ui-heading-ttl pull-left">Rewards Points</h3>
                                </div>
                                <div class="panel-body panel__ui-body rewards">
                                    <h2><?= CustomerReward::getUsablePoints($customer->id) ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
