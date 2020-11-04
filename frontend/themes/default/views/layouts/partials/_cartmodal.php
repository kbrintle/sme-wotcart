<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\components\CurrentStore;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use app\components\StoreUrl;
use common\models\sales\SalesQuote;

AppAsset::register($this);

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo   = SettingsSeo::find()->one();
?>

<!-- CART Modal START   -->
<div class="modal fade cart-modal" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                <h2 class="modal-title" id="cartModalLabel">Your Cart</h2>
            </div>
            <div class="modal-body">
                <div class="cart">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12" id="cart_modal_line_items">
                                <?php $cart_items = SalesQuote::getItems(); ?>
                                    <?php if (count($cart_items)): ?>
                                        <form data-action="<?php echo StoreUrl::to('cart/process'); ?>">
                                            <div class="cart-product-list pad-xs">
                                                <?php foreach ($cart_items as $cart_item): ?>
                                                    <?= $this->render('_line_item', [
                                                            'cart_item' => $cart_item
                                                        ]); ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                    <p>Your cart is empty.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo Html::a("View Cart", StoreUrl::to('cart'), ['class' => 'btn btn-primary btn-responsive']); ?>
                            <?php echo Html::a("Proceed to Checkout", StoreUrl::to('checkout'), ['class' => 'btn btn-primary btn-responsive']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CART Modal END   -->
