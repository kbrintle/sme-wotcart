<?php

use common\models\sales\SalesOrderItem;

$this->title = "Order#: " . $order->order_id;
?>


<div class="in-pdf" style="font-size: 11px">
    <div class="in-pdf-hd">
        <div class="row pad-bottom-lg">
            <div class="col-xs-4 col-md-4 details">
                <h4>Order Details:</h4>
                <span>Invoice: <?= $order->order_id ?></span><br/>
                <span>Order Date: <?= date("F j, Y") ?></span><br/>
                <span>Order Status: <?= $order->orderStatus->name ?></span><br/>
                <span>Purchased From: <?= $order->store->name ?></span><br/>
                <?php //if($customer):?>
                <span>Customer Name: <?= $order->customer_firstname . " " . $order->customer_lastname ?></span><br/>
                <span>Email: <?= $order->customer_email ?></span><br/>
                <?php //$customer_group = $customer->group->name ?>
                <?php //else: ?>
                <?php //$customer_group = 'Guest' ?>
                <?php //endif; ?>
                <span>Customer Group: <?php //echo $customer_group ?></span><br/>
                <h4>Payment Information:</h4>
                <?php if (isset($payment)): ?>
                    <span><?= ucfirst($payment->method) ?></span><br/>
                    <?php if ($payment->method == SalesOrderPayment::STRIPE): ?>
                        <span>Credit Card <span> ****-****-****-</span><?= $payment->cc_last4 ?></span><br/>
                        <span>Exp. <span> <?= $payment->cc_exp_month ?>/<?= $payment->cc_exp_year ?></span></span><br/>
                    <?php elseif ($payment->method == SalesOrderPayment::PAYPAL): ?>

                    <?php endif; ?>
                <?php else: ?>
                    <span>No Payment Method</span><br/>
                <?php endif; ?>
                <br/>
            </div>
            <div class="col-xs-3 col-md-3 bill">
                <h4>Bill To:</h4>
                <span><?= $order->billingAddress->firstname . " " . $order->billingAddress->lastname ?></span><br/>
                <span><?= $order->billingAddress->street ?></span><br/>
                <span><?= $order->billingAddress->city . ', ' . $order->billingAddress->countryRegion->code . ' ' . $order->billingAddress->postcode ?></span><br/>
                <br/>
                <?php if ($order->billingAddress->telephone): ?>
                    <span><?= 'T: ' . $order->billingAddress->telephone ?></span><br/>
                <?php endif; ?>
                <?php if ($order->billingAddress->fax): ?>
                    <span><?= 'F: ' . $order->billingAddress->fax ?></span><br/>
                <?php endif; ?>
            </div>
            <div class="col-xs-3 col-md-3 ship">
                <h4>Ship To:</h4>
                <span><?= $order->shippingAddress->firstname . " " . $order->shippingAddress->lastname ?></span><br/>
                <span><?= $order->shippingAddress->street ?></span><br/>
                <span><?= $order->shippingAddress->city . ', ' . $order->billingAddress->countryRegion->code . ' ' . $order->shippingAddress->postcode ?></span><br/>
                <br/>
                <?php if ($order->shippingAddress->telephone): ?>
                    <span><?= 'T: ' . $order->shippingAddress->telephone ?></span><br/>
                <?php endif; ?>
                <?php if ($order->shippingAddress->fax): ?>
                    <span><?= 'F: ' . $order->shippingAddress->fax ?></span><br/>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="in-pdf-bdy pad-bottom-md">
        <div class="in-pdf-items pad-bottom-md">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <table cellspacing="10" class="table table-stripped table-responsive"
                           style="border-top: none; margin-top: 60px; margin-bottom: 45px;">
                        <thead style="border: none;">
                        <tr style="border-top: none">
                            <th style="border-bottom: none; font-size: 10px;">
                                Product Name
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                SKU
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Qty
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Price
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Subtotal
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Tax
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Discount
                            </th>
                            <th style="border-bottom: none; font-size: 10px;">
                                Total
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $catalogProduct = new \common\models\catalog\CatalogProduct();
                        foreach ($order->items as $item):?>
                            <tr>
                                <td><?= $catalogProduct::getName($item->product_id) ?></td>
                                <td><?= $catalogProduct::getSku($item->product_id) ?></td>
                                <td><?= $item->qty_ordered ?></td>
                                <td><?= $item->price ?> </td>
                                <td><?= $item->subtotal ?></td>
                                <td><?= $item->tax_amount ?></td>
                                <td><?= $item->discount_amount ?></td>
                                <td><?= $item->row_total ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6 col-md-6">
                    <h4><?php if (!empty($order->customer_note)): ?>Notes:<?php endif; ?></h4>
                    <p>
                        <?= $order->customer_note ?>
                    </p>
                </div>
                <div class="col-xs-4 col-md-4 text-right" style="font-size: 14px; margin-left: 60px;">
                    <span><b>Subtotal:</b> <span style="margin-left: 30px;">$<?= $order->subtotal ?></span></span><br/>
                    <?php if ($order->discount_amount > 0): ?>
                        <span><b>Discount:</b> <span
                                    style="margin-left: 5%">$<?= ($order->coupon_code) ? ' - (' . $order->coupon_code . ')' : '' ?></span></span>
                        <br/>
                        <span>$<?= $order->discount_amount ?></span><br/>
                    <?php endif; ?>
                    <span><b>Shipping & Handling:</b> <span
                                style="margin-left: 30px">$<?= $order->shipping_amount ?></span></span><br/>
                    <span><b>Tax:</b> <span style="margin-left: 30px">$<?= $order->tax_amount ?></span></span><br/>
                    <span><b>Grand Total:</b> <span
                                style="margin-left: 30px">$<?= $order->grand_total ?></span></span><br/>
                </div>
            </div>
        </div>
    </div>
</div>
