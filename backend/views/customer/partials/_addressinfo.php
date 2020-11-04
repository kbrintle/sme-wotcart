<?php

use  \common\models\customer\CustomerAddress;

?>
<div class="panel panel__ui">
    <span class="glyphicon glyphicon-remove text-danger pull-right address-delete"
          address-id="<?= $address->address_id ?>"></span>
    <div class="panel-body address-select <?php if (isset($key)) {
        if ($key == 0) echo "selected";
    } ?>"
         address-id="<?= $address->address_id ?>" href="/admin/customer/get-address-form">
        <?php if ($new): ?>
            <h3>NEW</h3>
        <?php endif ?>
        <?= $address->firstname ?> <?= $address->lastname ?>
        <br>
        <?= $address->address_1 ?> <?= $address->address_2 ?>
        <br>
        <?= $address->city.',' ?>
                <?php if ($address->region_id): ?>

                    <?= (CustomerAddress::getRegionById($address->region_id)) ? CustomerAddress::getRegionById($address->region_id)->default_name : '' ?>
                <?php endif ?>

        <?= $address->postcode ?>
        <?php if ($address->phone): ?>
            <br>
            T: <?= $address->phone ?>
        <?php endif ?>
        <?php if ($address->fax): ?>
            <br>
            F: <?= $address->fax ?>
        <?php endif ?>
        <br>
        <?php if (!$new): ?>
        <input type="radio" value="<?= $address->address_id ?>" class="default-billing"
               title="Set as Default Billing Address" <?php if ($address->default_billing == true): ?> checked="checked"<?php endif; ?>>
        Default Billing
        <br>
        <input type="radio" value="<?= $address->address_id ?>" class="default-shipping" title="Set as Default Shipping Address" <?php if ($address->default_shipping == true): ?> checked="checked"<?php endif; ?>>
        Default Shipping
        <?php endif ?>
    </div>
</div>
