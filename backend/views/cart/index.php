<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Carts';
?>

<?php Yii::$app->controller->renderPartial('//layouts/partials/subheader'); ?>

<section class="">
    <div class="container-fluid">

        <div class="row action-row">
            <div class="col-md-12">

            </div>
        </div>

        <table class="table">
            <thead>
                <th>ID</th>
                <th>User</th>
                <th>Sales Order</th>
                <th>Status</th>
                <th>Created</th>
                <th>Updated</th>
            </thead>
            <tbody>
                <?php foreach($carts as $cart): ?>
                    <tr>
                        <td>
                            <?php echo Html::a($cart->id, ['view', 'id' => $cart->id]); ?>
                        </td>
                        <td><?php echo $cart->user_id ? $cart->user_id : 'Guest'; ?></td>
                        <td><?php echo $cart->sales_order_id; ?></td>
                        <td><?php echo $cart->status ? 'Closed' : 'Open'; ?></td>
                        <td><?php echo $cart->created_at ? date('m/d/y, g:i a', strtotime($cart->created_at)) : ''; ?></td>
                        <td><?php echo $cart->updated_at ? date('m/d/y, g:i a', strtotime($cart->updated_at)) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</section>
