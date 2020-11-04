<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;
use frontend\components\Assets;
use common\models\core\CoreConfig;
use backend\controllers\OrdersController;
use common\models\settings\SettingsStore;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogProductOption;
use common\models\core\CountryRegion;

$this->title = 'Order Confirmation - #'. $sales_order->order_id.'';
$this->params['breadcrumbs'][] = ['label' => 'Cart', 'url' => ['/sme/cart']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visible-print-inline-block  hidden-print">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="print-logo">
                    <img src="<?php echo Html::img(Assets::mediaResource(CoreConfig::getStoreConfig('general/design/logo'))) ?>"  />
                </div>
            </div>
        </div>
    </div>
</div>

<section class="">
    <div class="container-fluid">
        <div class="row pad-sm">
            <div class="container">
                <div class="cart">
                    <div class="row">
                        <div class="col-md-4 col-sm-8 col-xs-10">
                            <?= OrdersController::getDisplayOrderStatus($sales_order->order_id) ?>
                        </div>
                        <div class="col-md-2 col-md-offset-6 col-sm-2 col-sm-offset-2 hidden-print hidden-xs">
                           <a href="#" onclick="window.print();" style="color:black;" class="btn btn-responsive float-right">
                               <i class="material-icons align-middle">print</i>
                               Print
                           </a>
                        </div>
                    </div>
                    <div class="confirm">
                        <div class="panel panel__ui">
                            <div class="panel-heading panel__ui-heading clearfix">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="panel__ui-heading-ttl pull-left">Order Confirmation -
                                            <a href="#" class="color-primaryBlue">#<?= $sales_order->order_id; ?></a>
                                        </h3>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="cart-summary-top pull-right">
                                            <div class="cart-summary-total">
                                                <div class="cart-summary-label">
                                                    <span>Order Total:</span>
                                                </div>
                                                <div class="cart-summary-value">
                                                    <span class="cart-order-total">$<?= number_format($sales_order->grand_total, 2, '.', ','); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="panel-body panel__ui-body">
                                <?php $cart_items = $sales_order->items; ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="confirm-order-details">
                                            <div class="col-md-4">
                                                <div class="confirm-order">
                                                    <h3>customer info</h3>
                                                    <p class="m-b-xs confirm-order-customer"><?= $sales_order->customer_firstname; ?> <?= $sales_order->customer_lastname; ?></p>
                                                    <span class=""><?= $sales_order->customer_email; ?></span>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <?php $address = $sales_order->shippingAddress;
                                                    if($address): ?>
                                                    <div class="confirm-order">
                                                        <h3 class="">shipping address</h3>
                                                        <p class="m-b-xs confirm-order-customer"><?= $address->firstname; ?> <?= $address->lastname; ?></p>
                                                        <span class="block"><?= $address->street; ?></span>
                                                        <?php if(isset($address->street2)):?>
                                                        <span class="block"><?= $address->street2; ?></span>
                                                        <?php endif; ?>
                                                        <span class=""><?= $address->city; ?>, <?= CountryRegion::getRegionById($address->region_id)->code; ?> <?= $address->postcode; ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($sales_order->displayShippingMethod):?>
                                            <div class="col-md-4">
                                                <div class="confirm-order">
                                                    <h3 class="">delivery</h3>
<!--                                                    <p class="">Standard</p>-->
                                                    <small class=""><?= $sales_order->displayShippingMethod; ?></small>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <?php if($sales_order->customer_note):?>
                                                <div class="col-md-4">
                                                    <div class="confirm-order">
                                                        <h3 class="">Customer Notes</h3>
                                                        <!--                                                    <p class="">Standard</p>-->
                                                        <span class=""><?= $sales_order->customer_note; ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="col-md-4 col-md-offset-4">
                                                <?php $address = $sales_order->billingAddress;
                                                if($address): ?>
                                                    <div class="confirm-order">
                                                        <h3 class="">billing address</h3>
                                                        <p class="m-b-xs confirm-order-customer"><?= $address->firstname; ?> <?= $address->lastname; ?></p>
                                                        <span class="block"><?= $address->street; ?></span>
                                                        <?php if(isset($address->street2)):?>
                                                            <span class="block"><?= $address->street2; ?></span>
                                                        <?php endif; ?>
                                                        <span class=""><?= $address->city; ?>, <?= CountryRegion::getRegionById($address->region_id)->code; ?> <?= $address->postcode; ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?php $payment = $sales_order->payment;
                                                    if($payment): ?>
                                                <div class="confirm-order">
                                                    <h3 class="">Payment info</h3>
                                                    <small class="">
                                                        <?= $payment->cc_type; ?> Card ending in <?= $payment->cc_last4; ?>
                                                    </small>
                                                </div>
                                                <?php endif; ?>
                                                <?php if($sales_order->purchase_order):?>
                                                    <div class="confirm-order">
                                                        <h3 class="">Purchase Order</h3>
                                                        <!--                                                    <p class="">Standard</p>-->
                                                        <small class=""><?= $sales_order->purchase_order; ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="cart-product-list pad-top">
                                            <?php foreach ($cart_items as $cart_item):?>
                                                <?php $options = CatalogProduct::getProductCustomOptions($cart_item->product_id,$cart_item->sku)?>
                                                <div class="row">
                                                    <div class="cart-item">
                                                        <div class="col-md-1 hidden-print">
                                                            <div class="cart-product-image">
                                                                <?php $base_image = CatalogProduct::getGalleryImages($cart_item->product_id, 'base-image'); ?>
                                                                <?php if($base_image): ?>
                                                                    <?php echo Html::img(Assets::productResource($base_image->value), ['alt'=> CatalogProduct::getName($cart_item->product_id), 'class'=>'product-image img-responsive img-responsive-mobile']);?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <p class="cart-product-ttl"><?= CatalogProduct::getName($cart_item->product_id); ?>

                                                               <?php foreach ($options as $option): ?>
                                                                    </br> <?= $option ?>
                                                                <?php endforeach; ?>
                                                            </p>

                                                            <p class="">QTY <span
                                                                        class="cart-count"><?= $cart_item->qty_ordered; ?></span>
                                                            </p>
                                                        </div>
                                                        <div class="col-md-3 right-text">
                                                            <p>
                                                                $<?= number_format($cart_item->price * $cart_item->qty_ordered, 2, '.', ','); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row container-buffer">
                                    <div class="cart-summary col-md-4 col-md-offset-8">
                                        <table class="table table-cart">
                                            <tbody>
                                            <tr>
                                                <td class="cart-summary-label">
                                                    <span>Subtotal:</span>
                                                </td>
                                                <td class="cart-summary-value">
                                                    <span>$<?= number_format($sales_order->subtotal, 2, '.', ','); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cart-summary-label">
                                                    <span>Shipping:</span>
                                                </td>
                                                <td class="cart-summary-value">
                                                    <span>$<?= number_format($sales_order->shipping_amount, 2, '.', ','); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cart-summary-label">
                                                    <span>Sales Tax:</span>
                                                </td>
                                                <td class="cart-summary-value">
                                                    <span>$<?= number_format($sales_order->tax_amount, 2, '.', ','); ?></span>
                                                </td>
                                            </tr>
                                            <?php if($sales_order->discount_amount > 0):?>
                                            <tr>
                                                <td class="cart-summary-label">
                                                    <span>Discount: <?php echo ($sales_order->coupon_code) ? ' - ('. $sales_order->coupon_code .')' : '' ?></span>
                                                </td>
                                                <td class="cart-summary-value">
                                                    <span>- $<?= $sales_order->discount_amount ?></span>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($sales_order->discount_reward_amount > 0):?>
                                                <tr>
                                                    <td class="cart-summary-label">
                                                        <span>Reward Points Discount: </span>
                                                    </td>
                                                    <td class="cart-summary-value">
                                                        <span>- $<?= $sales_order->discount_reward_amount ?></span>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr class="cart-summary-total">
                                                <td class="cart-summary-label">
                                                    <span>Total:</span>
                                                </td>
                                                <td class="cart-summary-value">
                                                    <span class="cart-order-total">$<?= number_format($sales_order->grand_total, 2, '.', ','); ?></span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
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

<?php

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
echo ($settingsStore) ? $settingsStore->misc_success_scripts : ''

?>