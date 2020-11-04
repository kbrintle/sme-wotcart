<?php
/* @var $this yii\web\View */

use yii\web\View;

?>

<section class="checkout"
         ng-controller="CheckoutFormController">
    <div class="container-fluid">
        <div class="row pad-xs">
            <div class="container">
                <div class="row">
                    <?php //if( count(Yii::$app->cart->items) > 0 ): ?>
                    <?= $this->render('partials/_form', [
                        'model' => $model,
                        'quote' => $quote
                    ]); ?>
                    <?php //else: ?>
                    <?php //$this->render('partials/_no_cart'); ?>
                    <?php //endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade content-pad" id="freeProduct" tabindex="-1" role="dialog"></div>
