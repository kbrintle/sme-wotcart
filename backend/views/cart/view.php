<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = "Cart $model->id";
?>

<?php Yii::$app->controller->renderPartial('//layouts/partials/subheader'); ?>

<section class="">
    <div class="container-fluid">

        <div class="row action-row">
            <div class="col-md-12">

            </div>
        </div>

        <div class="row form-group">
            <div class="col-xs-12 col-md-4">
                <label>User:</label> <?php echo $model->user_id ? $model->user_id : 'Guest'; ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <label>Sales Order:</label> <?php echo $model->sales_order_id; ?>
            </div>
            <div class="col-xs-12 col-md-4">
                <label>Status:</label> <?php echo $model->status ? 'Closed' : 'Open'; ?>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-xs-12 col-md-4">
                <label>Created:</label> <?php echo $model->created_at ? date('m/d/y, g:i a', strtotime($model->created_at)) : ''; ?>
            </div>
            <div class="col-xs-12 col-md-4 last">
                <label>Updated:</label> <?php echo $model->updated_at ? date('m/d/y, g:i a', strtotime($model->updated_at)) : ''; ?>
            </div>
            <hr>
        </div>


        <div class="row form-group">
            <div class="col-xs-12">
                <h3>Line Items</h3>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-xs-12">
                <?php $line_items = unserialize($model->products); ?>
                <table class="table">
                    <thead>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </thead>
                    <tbody>
                    <?php foreach($line_items as $line_item): ?>
                        <tr>
                            <td><?php echo $line_item->findAttribute('name'); ?></td>
                            <td><?php echo $line_item->getQuantity(); ?></td>
                            <td>$<?php echo $line_item->getDisplayPrice(); ?></td>
                            <td>$<?php echo $line_item->getDisplayCost(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
