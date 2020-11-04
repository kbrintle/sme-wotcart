<?php $catalogProduct = new \common\models\catalog\CatalogProduct(); ?>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="padding: 30px">
            <h2 style="color: #2196F3; font-size: 24px;">Your order is confirmed!</h2>
            <p style="line-height: 1.5; color: #77909C; font-size: 12px; letter-spacing: 1px;">Hi <?= $order->customer_firstname ?>,
                <br/>
                <br/>
                Thanks for shopping with us! Your new mattress is on its way!
            </p>
        </td>
    </tr>
    <tr style="background: #eceff1;">
        <td style="padding: 15px 30px;">
            <span style="float: left; padding: 12px 0;">Order: <a href="" style="color: #2196F3;">#<?= $order->order_id ?></a></span>
<!--            <a href="#" style="text-decoration: none; float: right; font-size: 16px; color: #FFF; background: #2196F3; border-radius: 4px; padding: 10px 30px;">-->
<!--                Manage Order-->
<!--            </a>-->
        </td>
    </tr>
    <tr>
        <td style="padding: 30px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                <?php foreach ($order->items as $item): ?>
                        <tr>
                            <td style="padding: 15px 0; color: #77909C; font-size: 12px;">
                                <div style="color: #2196F3; font-size: 16px;"><?= $catalogProduct::getName($item->product_id) ?></div>
                            </td>
                            <td style="padding: 15px 0; color: #77909C; font-size: 18px; text-align: right;">
                                <?= $item->price ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="border-top:solid 1px #F5F7F8;">
                        <td></td>
                        <td style="padding: 15px 0;">
                            <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right; padding-right: 30px">Subtotal</span>
                            <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right; padding-right: 30px">+ Tax</span>
                            <?php if($order->discount_amount > 0):?>
                                <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right; padding-right: 30px">Discount: <?= ($order->coupon_code) ? ' - ('. $order->coupon_code .')' : '' ?></span>
                            <?php endif; ?>
                            <span style="font-weight: bold; margin-bottom: 15px; font-size: 16px; color: #004270; display: block; text-align: right; padding-right: 30px">Total</span>
                        </td>
                        <td style="padding: 15px 0;">
                            <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right;">$<?= $order->subtotal ?></span>
                            <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right;"> $<?= $order->tax_amount ?></span>
                            <?php if($order->discount_amount > 0):?>
                                <span style="margin-bottom: 15px; font-size: 16px; color: #77909C; display: block; text-align: right;">$<?= $order->discount_amount ?></span>
                            <?php endif; ?>
                            <span style="font-weight: bold; margin-bottom: 15px; font-size: 16px; color: #004270; display: block; text-align: right;">$<?= $order->grand_total ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <p style="line-height: 1.5; color: #77909C; font-size: 12px; letter-spacing: 1px;">By placing your order, you agree to <?= Yii::$app->name; ?> Privacy Notice and Conditions of Use. Unless otherwise noted, items sold by americasmattress.com LLC are subject to sales tax in select states in accordance with the applicable laws of that state. If your order contains one or more items from a seller other than americasmattress.com LLC , it may be subject to state and local sales tax, depending upon the seller’s business policies and the location of their operations. Learn more about tax and seller information.</p>
            <p style="line-height: 1.5; color: #77909C; font-size: 12px; letter-spacing: 1px;">This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</p>
        </td>
    </tr>
    </tbody>
</table>