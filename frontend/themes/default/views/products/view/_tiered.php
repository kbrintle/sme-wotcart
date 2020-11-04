<?php

use common\models\catalog\CatalogProduct;

$tiered_prices = CatalogProduct::getTieredPricing($product_id);

if($tiered_prices):?>
    <div class="form-group">
        <label class="control-label">Price Breaks</label>
    </div>
    <ul class="tier-prices list-unstyled">
        <?php foreach ($tiered_prices as $tiers):?>
            <li class="tier-price tier">
                <?php echo ''. $tiers->qty .' for $' . $tiers->value ." each"?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
