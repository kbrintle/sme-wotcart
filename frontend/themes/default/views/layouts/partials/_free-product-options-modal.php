<?php

use yii\helpers\Html;
use common\models\catalog\CatalogProductOption;
use common\models\promotion\PromotionDiscount;
use app\components\StoreUrl;

$discountPercent = 1;
if ($discount = PromotionDiscount::productHasTargetedDiscount($product->id)) {
    if ($discount->type == "percent") {
        if ($discount->amount == 100) {
            $discountPercent = 0;
        } else {
            $discountPercent = $discount->amount / 100;
        }
    }
}
if ($options): ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title" id="emailStoreLabel">Select Options for <?= $product_name ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group quantity m-b--sm">
                    <div id="options" class="custom-options"
                         data-delimiter="<?= Yii::$app->params['options-sku-delimiter'] ?>">
                    </div>
                    <div class="row">
                        <?php foreach ($options as $option): ?>
                            <div class="col-md-12">
                                <?php $optionValues = CatalogProductOption::getOptionValues($option->option_id); ?>
                                <label> <?= $option->title; ?> </label>
                                <?php if ($option->type == CatalogProductOption::TYPE_DROPDOWN): ?>
                                    <div class="option-group" id="<?= $option->option_id ?>"
                                         isrequired="<?= $option->is_required ?>"
                                         type="<?= $option->type ?>">
                                        <select name="<?= $option->option_id ?>"
                                                class="form-control custom-option-sel">
                                            <option name="null" data-sku
                                            "" data-price="0.00">--Select One--</option>
                                            <?php foreach ($optionValues as $optionValue): ?>
                                                <option data-sku="<?= $optionValue->sku ?>"
                                                        data-price="<?= $optionValue->price * $discountPercent ?>">
                                                    <?= $optionValue->title ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select></div>
                                <?php elseif ($option->type == CatalogProductOption::TYPE_RADIO): ?>
                                    <div class="option-group" id="<?= $option->option_id ?>"
                                         isrequired="<?= $option->is_required ?>"
                                         type="<?= $option->type ?>">
                                        <?php foreach ($optionValues as $optionValue): ?>
                                            <div class="radio">
                                                <label><input type="radio" name="<?= $option->option_id ?>"
                                                              class="custom-option-sel"
                                                              data-sku="<?= $optionValue->sku ?>"
                                                              data-price="<?= $optionValue->price * $discountPercent ?>"> <?= $optionValue->title ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php elseif ($option->type == CatalogProductOption::TYPE_CHECKBOX): ?>
                                    <div class="option-group" id="<?= $option->option_id ?>"
                                         isrequired="<?= $option->is_required ?>"
                                         type="<?= $option->type ?>">
                                        <?php foreach ($optionValues as $optionValue): ?>
                                            <div class="checkbox">
                                                <label><input type="checkbox" class="custom-option-sel"
                                                              name="<?= $option->option_id ?>"
                                                              class="" data-sku="<?= $optionValue->sku ?>"
                                                              data-price="<?= $optionValue->price * $discountPercent ?>"> <?= $optionValue->title ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <br>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a id="addCart" class="cart-add btn btn-primary btn-xl btn-block btn-pad-btm"
                               data-action="<?php echo StoreUrl::to('cart/promocode'); ?>"
                               data-sku="<?= $product_sku ?>"
                               data-free="true"
                               data-pid="<?= $product->id ?>">Select</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


