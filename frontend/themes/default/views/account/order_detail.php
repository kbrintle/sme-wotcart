<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use frontend\components\Assets;
use common\models\catalog\CatalogProductGallery;
use common\models\core\CountryRegion;
use common\models\catalog\CatalogProduct;

?>
<div class="account content-pad">
    <div class="account-overview ">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3 sidebar no-pad">
                    <?php echo $this->render('_nav.php') ?>
                </div>
                <div class="col-sm-9 col-md-9 no-pad cart">
                    <div class="container-fluid pad-top confirm">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel__ui">
                                    <div class="panel-heading panel__ui-heading clearfix">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h3 class="panel__ui-heading-ttl pull-left">Order Confirmation -
                                                    #<?= isset($order->order_id) ? $order->order_id : ''; ?>
                                                </h3>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="cart-summary-top pull-right">
                                                    <div class="cart-summary-total">
                                                        <div class="cart-summary-label">
                                                            <span>Order Total:</span>
                                                        </div>
                                                        <div class="cart-summary-value">
                                                            <span class="cart-order-total">$<?= number_format($order->grand_total, 2, '.', ''); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body panel__ui-body">
                                        <?php $cart_items = $order->items; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="confirm-order-details">
                                                    <div class="col-md-4">
                                                        <div class="confirm-order">
                                                            <h3>customer info</h3>
                                                            <p class="confirm-order-customer"><?= $order->customer_firstname; ?> <?= $order->customer_lastname; ?></p>
                                                            <small class=""><?= $order->customer_email; ?></small>
                                                            <br>
                                                            <!--                                                        <small class="">(207) 633-5730</small>-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <?php $address = $order->shippingAddress;
                                                        if($address): ?>
                                                            <div class="confirm-order">
                                                                <h3 class="">shipping address</h3>
                                                                <p class="confirm-order-customer"><?= $address->firstname; ?> <?= $address->lastname; ?></p>
                                                                <small class=""><?= $address->street; ?></small>
                                                                <br>
                                                                <small class=""><?= $address->city; ?>, <?= CountryRegion::getRegionById($address->region_id)->code ?> <?= $address->postcode; ?></small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="confirm-order">
                                                            <h3 class="">delivery</h3>
                                                            <!--                                                    <p class="">Standard</p>-->
                                                            <small class=""><?= $order->displayShippingMethod; ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-md-offset-4">
                                                        <?php $address = $order->billingAddress;
                                                        if($address): ?>
                                                            <div class="confirm-order">
                                                                <h3 class="">billing address</h3>
                                                                <p class="confirm-order-customer"><?= $address->firstname; ?> <?= $address->lastname; ?></p>
                                                                <small class=""><?= $address->street; ?></small>
                                                                <br>
                                                                <small class=""><?= $address->city; ?>, <?= CountryRegion::getRegionById($address->region_id)->code ?> <?= $address->postcode; ?></small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <?php $payment = $order->payment;
                                                        if($payment): ?>
                                                            <div class="confirm-order">
                                                                <h3 class="">Payment info</h3>
                                                                <small class="">
                                                                    <?= $payment->cc_type; ?> Card ending in <?= $payment->cc_last4; ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="cart-product-list pad-top">
                                                    <?php foreach ($cart_items as $cart_item): ?>
                                                        <div class="row">
                                                            <div class="cart-item">
                                                                <div class="col-md-1">
                                                                    <div class="cart-product-image">
                                                                        <?php
                                                                            $base_image = CatalogProductGallery::getImages($cart_item->product_id);
                                                                            $image = Assets::productResource($base_image);
                                                                        ?>
                                                                        <img src="<?php echo $image ?>"  class="zoom center-block img-responsive img-responsive-mobile"/>

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <p class="cart-product-ttl"><?= $cart_item->name ?>
                                                                        <?php $options = CatalogProduct::getProductCustomOptions($cart_item->product_id, $cart_item->sku); ?>
                                                                        <?php foreach ($options as $option): ?>
                                                                            </br> <?= $option ?>
                                                                        <?php endforeach; ?>
                                                                    </p>
                                                                    <p class="">SKU <span class="cart-count"><?= $cart_item->sku; ?></span></p>
                                                                    <p class="">QTY <span class="cart-count"><?= $cart_item->qty_ordered; ?></span></p>
                                                                </div>
                                                                <div class="col-md-3 right-text">
                                                                    <p>$<?= number_format($cart_item->price, 2, '.', ''); ?></p>
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
                                                            <span>$<?= number_format($order->subtotal, 2, '.', ''); ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="cart-summary-label">
                                                            <span>Shipping:</span>
                                                        </td>
                                                        <td class="cart-summary-value">
                                                            <span>$<?= number_format($order->shipping_amount, 2, '.', ''); ?></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="cart-summary-label">
                                                            <span>Sales Tax:</span>
                                                        </td>
                                                        <td class="cart-summary-value">
                                                            <span>$<?= number_format($order->tax_amount, 2, '.', ''); ?></span>
                                                        </td>
                                                    </tr>
                                                    <?php if($order->discount_amount > 0):?>
                                                        <tr>
                                                            <td class="cart-summary-label">
                                                                <span>Discount: <?php echo ($order->coupon_code) ? ' - ('. $order->coupon_code .')' : '' ?></span>
                                                            </td>
                                                            <td class="cart-summary-value">
                                                                <?php echo $order->discount_amount ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                    <tr class="cart-summary-total">
                                                        <td class="cart-summary-label">
                                                            <span>Total:</span>
                                                        </td>
                                                        <td class="cart-summary-value">
                                                            <span class="cart-order-total">$<?= number_format($order->grand_total, 2, '.', ''); ?></span>
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
