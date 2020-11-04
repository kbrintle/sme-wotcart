<?php

use app\components\StoreUrl;
use frontend\controllers\CartController;

$code = CartController::getPromoCode();
$discount = CartController::getPromoDiscount();

?>
<div id="order_summary">
    <table class="table table-cart">
        <tbody>
        <tr>
            <td class="cart-summary-label">
                <span>Subtotal:</span>
            </td>
            <td class="cart-summary-value">
                <span>$<?= $quote["subTotal"] ?></span>
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
        <tr id="promo" data-page="cart" data-action="<?php echo StoreUrl::to('cart/process'); ?>">
            <?php if (empty($discount)): ?>
            <td class="cart-summary-label">
                <input type="text" id="promo-code" class="form-control" placeholder="Enter a Promo Code">
                <small class="text-danger"></small>
            </td>
            <td class="cart-summary-value">
                <a id="promo-apply" class="btn btn-default" data-action="<?php echo StoreUrl::to('cart/promocode'); ?>">Apply</a>
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