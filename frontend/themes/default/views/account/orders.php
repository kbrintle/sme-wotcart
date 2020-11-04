<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;
use common\models\sales\SalesOrder;

$this->title = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account pad-xs">
    <div class="account-orders">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="sidebar">
                        <?php echo $this->render('_nav.php') ?>
                    </div>
                </div>
                <div class="col-sm-9 col-lg-9 col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel__ui">
                                <div class="panel-heading panel__ui-heading">
                                    <h3 class="panel__ui-heading-ttl">My Orders</h3>
                                </div>
                                <div class="panel panel__ui-body">
                                    <?php if($customer->orders):?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h4>
                                                    Order Number
                                                </h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Date
                                                </h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>
                                                    Order Status
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <?php
                                                $orders = SalesOrder::find()->where(['customer_id' => $customer->id])->orderby(['created_at'=>SORT_DESC])->all();

                                                foreach($orders as $order): ?>
                                                <div class="col-md-4">
                                                    <a href="<?php echo StoreUrl::to("account/order/".$order->order_id) ?>">
                                                        <?php echo $order->order_id;?>
                                                    </a>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>
                                                        <?php echo date('M d, Y g:i a', $order->created_at);?>
                                                    </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>
                                                        <?php echo ($order->orderStatus) ? $order->orderStatus->name : 'Status Not Available' ;?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>There are currently no orders in your account.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
