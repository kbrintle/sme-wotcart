<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use common\models\catalog\CatalogProduct;
use frontend\controllers\CartController;

$code = CartController::getPromoCode();
$discount = CartController::getPromoDiscount();

?>
<div class="row cart">
    <div class="col-sm-12">
        <div class="panel panel__ui">
            <div class="panel-heading panel__ui-heading clearfix">
                <h3 class="panel__ui-heading-ttl pull-left">Order Summary</h3>
                <div class="pull-right">
                    <a href="<?php echo StoreUrl::to('cart'); ?>">Edit</a>
                </div>
            </div>
            <div class="panel-body panel__ui-body">
                <?php if (isset($quote)): ?>
                    <div class="cart-summary">
                        <div class="cart-product-list pad-btm">
                            <div class="cart-item">
                                <?php foreach ($quote["items"] as $quote_item): ?>

                                    <div class="row">
                                        <div class="cart-product-description">
                                            <div class="col-md-12 clearfix">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <p>
                                                            <?= CatalogProduct::getParentName($quote_item['id']) ?>
                                                            <?= $quote_item["name"]; ?>
                                                        </p>
                                                        <p>
                                                            <?php if ($quote_item['options']): ?>
                                                            <?php foreach ($quote_item['options'] as $option): ?>
                                                                <?= " " . $option; ?>
                                                            <?php endforeach; ?>
                                                        </p>
                                                        <?php endif; ?></div>
                                                    <div class="col-md-3">
                                                        <span class="cart-product-price pull-right">$<?= $quote_item["itemsPrice"] ?></span>
                                                    </div>
                                                </div>

                                                <span class="cart-count cart-product-qty">Qty: <?= $quote_item["qty"] ?></span>
                                                </br>
                                            </div>
                                        </div>
                                    </div>
                                    </br>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div id="order_summary">
                            <table class="table table-cart">
                                <tbody>
                                <tr>
                                    <td class="cart-summary-label">
                                        <span>Subtotal:</span>
                                    </td>
                                    <td class="cart-summary-value">
                                        <span>$<?= $quote['subTotal'] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cart-summary-label">
                                        <span>Shipping:</span>
                                    </td>
                                    <td class="cart-summary-value">
                                        <span>$<?= $quote["shippingPrice"] ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cart-summary-label">
                                        <span>Sales Tax:</span>
                                    </td>
                                    <td class="cart-summary-value">
                                        <span class="cart-tax">$<?= $quote["salesTax"] ?></span>
                                    </td>
                                </tr>
                                <tr id="promo" data-page="checkout"
                                    data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                                    <?php if (empty($discount)): ?>
                                    <td class="cart-summary-label">
                                        <input type="text" id="promo-code" class="form-control"
                                               placeholder="Enter a Promo Code" autocomplete="no">
                                        <small class="text-danger"></small>
                                    </td>
                                    <td class="cart-summary-value">
                                        <a id="promo-apply" class="btn btn-default"
                                           data-action="<?php echo StoreUrl::to('cart/promocode'); ?>">Apply</a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <td class="cart-summary-label">
                                        <a id="promo-remove" class="btn btn-default"
                                           data-action="<?php echo StoreUrl::to('cart/promocode'); ?>">Remove</a>
                                    </td>
                                    <td class="cart-summary-value">
                                        <span class="text-danger">&ndash; $<?= $discount ?></span><br>
                                        <small><?= $code ?></small>
                                    </td>

                                <?php endif; ?>
                                </tr>
                                <?php if ($quote['rewardValue'] > 0): ?>
                                    <tr>
                                        <td class="cart-summary-label">
                                            <a id="reward-remove" class="btn btn-default"
                                               data-action="<?php echo StoreUrl::to('cart/reward-points'); ?>">Remove</a>
                                        </td>
                                        <td class="cart-summary-value">
                                            <span class="text-danger">&ndash; $<?= $quote['rewardValue'] ?></span><br>
                                            <small>Reward points applied</small>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr class="cart-summary-total">
                                    <td class="cart-summary-label">
                                        <span>Total:</span>
                                    </td>
                                    <td class="cart-summary-value">
                                        <span class="cart-order-total">$<?= $quote['grandTotal'] ?></span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="checkout-buttons">
            <?= Html::submitButton('Place Order', ['class' => 'btn btn-primary btn-responsive btn-block btn-xl']); ?>
        </div>
    </div>
</div>


<!--    <div class="checkout-terms">-->
<!--        <p>By clicking “Place your order” you agree to America’s Mattress <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.</p>-->
<!--    </div>-->

