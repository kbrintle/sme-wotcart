<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use yii\bootstrap\ActiveForm;
use common\models\sales\SalesQuote;

$quote = SalesQuote::getItems();
$this->title = 'Your Cart';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="">
    <div class="container-fluid">
        <div class="row pad-xs">
            <div class="container">
                <div class="cart cart-page-no-modal">
                    <div class="panel panel__ui">
                        <div class="panel-heading panel__ui-heading clearfix">
                            <h3 class="panel__ui-heading-ttl pull-left">Your Cart</h3>
                            <div class="cart-overview clearfix">
                                <div class="pull-right">
                                    <?php if (isset($quote['items'])): ?>
                                        <?php echo Html::a("Proceed to Checkout", StoreUrl::to('checkout'), ['class' => 'btn-responsive btn btn-primary checkout-buttons']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body panel__ui-body">
                            <?php if (isset($quote['items'])): ?>
                            <div class="row">
                                <form id="product-form" data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                                    <div class="col-md-12" id="cart_line_items">
                                        <form data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                                            <div class="cart-product-list pad-xs">
                                                <?php foreach ($quote['items'] as $cart_item): ?>
                                                    <?= $this->render('../layouts/partials/_line_item', [
                                                        'quote_item' => $cart_item
                                                    ]); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </form>
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="gmap_container" class="locations-singleview-map"></div>
                                    <span>&nbsp;</span>
                                </div>
                                <div class="col-md-4">
                                    <?php if (isset($quote['items'])): ?>
                                    <div class="cart-summary pad-top clearfix">
                                        <?php echo $this->render('../cart/partials/_order_summary', [
                                            'quote' => $quote
                                        ]); ?>
                                        <?php endif; ?>
                                        <div class="pull-right pad-top">
                                            <?php if (isset($quote['items'])): ?>
                                                <?php echo Html::a("Proceed to Checkout", StoreUrl::to('checkout'), ['class' => 'btn-responsive btn btn-primary checkout-buttons']); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                                <p>Your cart is empty.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal -->
<div class="modal modal__ui fade" id="emailQuote" tabindex="-1" role="dialog" aria-labelledby="emailQuoteLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php $form = ActiveForm::begin([
                'action' => StoreUrl::to('cart/email'),
                'method' => 'post',
                'id' => 'email-store',
                'class' => 'email-quote'
            ]); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="emailStoreLabel">Email you or someone else this quote to bring to your nearest <?= Yii::$app->name; ?>.</h4>
                <br/>
                <p class="message alert-success hidden">Your quote has been emailed. We hope to see you in our stores soon.</p>
                <p class="message alert-error hidden">There was an issue sending your quote.</p>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?= $form->field($quoteForm, 'email') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($quoteForm, 'body')->textarea(['rows' => 6])->label('Message') ?>
                </div>

            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Email Quote', ['class' => 'btn btn-primary', 'name' => 'email-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="modal fade content-pad" id="freeProduct" tabindex="-1" role="dialog">

</div>


