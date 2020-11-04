<?php

use yii\helpers\Html;
use common\models\sales\SalesOrderPayment;
use yii\widgets\ActiveForm;
use common\models\sales\SalesOrderStatus;
use yii\helpers\Url;
use common\models\core\CountryRegion;
use common\models\catalog\CatalogProduct;
use backend\components\CurrentUser;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = "Order#: " . $order->order_id;
$status = SalesOrderStatus::findOne($order->status);

if ($status) {
    switch ($status->name) {
        case 'Pending':
            if (!CurrentUser::isOperations()) {
                $statusButton = Html::a('Approve', Url::to([
                    'orders/process',
                    'id' => $order->order_id,
                    'status' => 'accept'
                ]), ['class' => 'btn btn-primary']);
            } else {
                $statusButton = "";
            }
            break;
        case 'Processing':
            if (!CurrentUser::isStoreAdmin()) {
                $statusButton = Html::a('Mark Shipped', Url::to([
                    'orders/process',
                    'id' => $order->order_id,
                    'status' => 'complete'
                ]), ['class' => 'btn btn-secondary']);
            } else {
                $statusButton = "";
            }
            break;

        default:
            $statusButton = '';
    }
}
?>

<?php $form = ActiveForm::begin(); ?>
<div class="container-fluid pad-xs">
    <div class="order-view">

        <div class="row action-row">
            <div class="col-md-12">

                <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['index']), ['title' => 'Back', 'class' => 'btn btn-default btn-spacer pull-left']); ?>
                <button type="submit" href="" class="pull-right btn btn-xs">Update Order</button>
                <?= Html::a('Send Email', ['email-confirmation', 'id' => $order->order_id], [
                    'class' => 'btn btn-primary pull-right btn-spacer',
                    'data' => [
                        'confirm' => 'Are you sure you want to resend an order confirmation to ' . $order->customer_email . '?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Print Invoice', ['create-invoice', 'id' => $order->order_id], [
                    'class' => 'btn btn-primary btn-spacer pull-right',
                    'target' => '_blank'
                ]) ?>
                <?= isset($statusButton) ? $statusButton : '' ?>
                <?= Html::a('Cancel/Void', ['cancel', 'id' => $order->order_id], [
                    'class' => 'btn btn-default btn-spacer pull-right',
                    'data' => [
                        'confirm' => 'Are you sure you want to cancel this order?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel__ui">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6 order-details">
                                <h4 class="pull-left">Order Details</h4>
                            </div>
                            <div class="col-md-3 order-billing-address">
                                <h4 class="pull-left">Billing Address</h4>
                            </div>
                            <div class="col-md-3 order-shipping-address">
                                <h4 class="pull-left">Shipping Address</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 order-details">
                                <div class="row">
                                    <div class="col-md-12">
                                        <dl class="inline">
                                            <dt>Order Date:</dt>
                                            <dd><?= date("F j, Y, g:i a", $order->created_at) ?></dd>
                                            <dt>Order Status:</dt>
                                            <dd>
                                                <?php if (isset($order->orderStatus->name)): ?>
                                                    <?= $order->orderStatus->name ?>
                                                <?php endif; ?>
                                            </dd>
                                            <dt>Purchased From:</dt>
                                            <dd><?= $store->name ?></dd>
                                            <?php if ($order->purchase_order): ?>
                                                <dt>Purchase Order #:</dt>
                                                <dd><input type="text" name="purchase_order" value="<?= $order->purchase_order ?>"/> </dd>
                                            <?php endif; ?>
                                            <?php if ($customer): ?>
                                                <dt>Customer Name:</dt>
                                                <dd><?= $order->customer_firstname . " " . $order->customer_lastname ?></dd>
                                                <dt>Email:</dt>
                                                <dd><?= $order->customer_email ?></dd>
                                                <?php $customer_group = $customer->group->name ?></dd>
                                            <?php else: ?>
                                                <?php $customer_group = 'Guest' ?></dd>
                                            <?php endif; ?>
                                            <dt>Customer Group:</dt>
                                            <dd><?= $customer_group ?></dd>
                                        </dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="order-details-payment">
                                            <h4>Payment Information:</h4>
                                            <ul class="list-unstyled">
                                                <?php if (isset($payment)): ?>
                                                    <li><?= ucfirst($payment->method) ?></li>
                                                    <?php if ($payment->method == SalesOrderPayment::STRIPE): ?>
                                                        <li>Credit Card
                                                            <span> ****-****-****-</span><?= $payment->cc_last4 ?></li>
                                                        <li>Exp.
                                                            <span> <?= $payment->cc_exp_month ?>/<?= $payment->cc_exp_year ?></span>
                                                        </li>
                                                    <?php elseif ($payment->method == SalesOrderPayment::PAYPAL): ?>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <li>No Payment Method</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php if ($order->shipping_description): ?>
                                            <div class="order-shipping-handling">
                                                <h4>Shipping & Handling Information:</h4>
                                                <?= $order->shipping_description ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 order-billing-address">
                                <address>
                                    <ul class="list-unstyled">
                                        <li><?= $billingAddress->firstname . " " . $billingAddress->lastname ?></li>
                                        <li><?= $billingAddress->street ?></li>
                                        <?php if (isset($billingAddress->street2)): ?>
                                            <li><?= $billingAddress->street2 ?></li>
                                        <?php endif; ?>
                                        <li><?= $billingAddress->city . ', ' . CountryRegion::getRegionById($billingAddress->region_id)->code . ' ' . $billingAddress->postcode ?></li>
                                        </br>
                                        <?php if ($billingAddress->telephone): ?>
                                            <li><?= 'T: ' . $billingAddress->telephone ?></li>
                                        <?php endif; ?>
                                        <?php if ($billingAddress->fax): ?>
                                            <li><?= 'F: ' . $billingAddress->fax ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </address>
                            </div>
                            <div class="col-md-3 order-shipping-address">
                                <address>
                                    <ul class="list-unstyled">
                                        <li><?= $shippingAddress->firstname . " " . $shippingAddress->lastname ?></li>
                                        <li><?= $shippingAddress->street ?></li>

                                        <?php if (isset($shippingAddress->street2)): ?>
                                            <li><?= $shippingAddress->street2 ?></li>
                                        <?php endif; ?>

                                        <li><?= $shippingAddress->city . ', ' . CountryRegion::getRegionById($shippingAddress->region_id)->code . ' ' . $shippingAddress->postcode ?></li>
                                        </br>
                                        <?php if ($shippingAddress->telephone): ?>
                                            <li><?= 'T: ' . $shippingAddress->telephone ?></li>
                                        <?php endif; ?>
                                        <?php if ($shippingAddress->fax): ?>
                                            <li><?= 'F: ' . $shippingAddress->fax ?></li>
                                        <?php endif; ?>
                                    </ul>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xs-12">
                <div class="panel panel__ui">
                    <form>
                        <div class="panel-heading clearfix">
                            <h4 class="pull-left">Items Ordered</h4>
                        </div>
                        <div class="panel-body">
                            <table cellspacing="10" class="table table-stripped table-responsive table-condensed">
                                <thead>
                                <tr>
                                    <th>
                                        Product Name
                                    </th>
                                    <th>
                                        SKU
                                    </th>
                                    <th>
                                        Qty
                                    </th>
                                    <th>
                                        Price
                                    </th>
                                    <th>
                                        Subtotal
                                    </th>
                                    <th>
                                        Tax Amount
                                    </th>
                                    <th>
                                        Discount Amount
                                    </th>
                                    <th>
                                        Total
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $catalogProduct = new CatalogProduct();
                                foreach ($order->items as $item):?>
                                    <tr>
                                        <td><?= CatalogProduct::getName($item->product_id); ?>
                                            <?php $options = CatalogProduct::getProductCustomOptions($item->product_id, $item->sku); ?>
                                            <?php foreach ($options as $option): ?>
                                                </br> <?= $option ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= $item->sku ?></td>
                                        <td><input type="number" class="text-center" value="<?= $item->qty_ordered ?>"
                                                   name="qty[<?= $item->id ?>]"/></td>
                                        <td><?= $item->price ?> </td>
                                        <td><?= $item->subtotal ?></td>
                                        <td><?= $item->tax_amount ?></td>
                                        <td><?= $item->discount_amount ?></td>
                                        <td><?= $item->row_total ?></td>
                                        <td><i class="delete-product-row far fa-trash-alt"></i></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                </div>
                </form>
            </div>

            <div class="col-md-8 col-xs-12">
                <div class="panel panel__ui">
                    <div class="panel-heading"><h4>Order Comments</h4></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="customer_note"><?= $order->customer_note ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <div class="panel panel__ui">
                    <div class="panel-heading"><h4>Order Totals</h4></div>
                    <div class="panel-body">
                        <dl class="inline">
                            <dt>Subtotal:</dt>
                            <dd><?= $order->subtotal ?></dd>
                            <?php if ($order->discount_amount > 0): ?>
                                <dt>
                                    Discount: <?= ($order->coupon_code) ? ' - (' . $order->coupon_code . ')' : '' ?>
                                </dt>
                                <dd><?= $order->discount_amount ?></dd>
                            <?php endif; ?>
                            <?php if ($order->discount_reward_amount > 0): ?>
                                <dt>
                                    Reward Points Discount:
                                </dt>
                                <dd> -<?= $order->discount_reward_amount ?></dd>
                            <?php endif; ?>
                            <dt>Shipping & Handling:</dt>
                            <dd><?= $order->shipping_amount ?></dd>
                            <dt>Tax:</dt>
                            <dd><?= $order->tax_amount ?></dd>
                            <dt>Grand Total:</dt>
                            <dd><?= $order->grand_total ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
<?php ActiveForm::end(); ?>




