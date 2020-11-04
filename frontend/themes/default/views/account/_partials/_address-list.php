<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use common\models\core\CountryRegion;

?>

<div id="address-list" class="col-md-12">
    <div class="panel panel__ui">
        <div class="panel-heading panel__ui-heading">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="panel__ui-heading-ttl">My Addresses</h3>
                </div>
                <div class="col-md-2 col-lg-offset-6">
                    <?= Html::a('Add New', null, ['id' => "address_new", 'data-action' => "new", 'data-url' => StoreUrl::to("account/addresses"), 'data-aid' => "new", 'class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>
        <div class="panel panel__ui-body">
            <?php if ($addresses): ?>
                <div class="row">
                    <div class="col-md-4">
                        <h4>
                            Address
                        </h4>
                    </div>
                    <div class="col-md-2 text-center">
                        <h4>
                            Type
                        </h4>
                    </div>
                    <div class="col-md-3 text-center">
                        <h4>
                            Defaults
                        </h4>
                    </div>
                    <div class="col-md-3 text-center">
                    </div>
                </div>
                <br><br>
                <?php foreach ($addresses as $address): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <p><?= "$address->firstname $address->lastname"; ?></p>
                            <p><?= $address->address_1; ?></p>
                            <p><?= $address->address_2; ?></p>
                            <p><?php if (isset($address->city)): ?>
                                    <?= $address->city ?>,
                                <?php endif; ?>
                                <?php if ($region = CountryRegion::getRegionById($address->region_id)): ?>
                                    <?= $region->code ?>
                                <?php endif; ?>
                                <?php if (isset($address->postcode)): ?>
                                    <?= $address->postcode ?>
                                <?php endif; ?>
                            <p>
                        </div>

                        <div class="col-md-2 text-center">
                            <p>
                                <?php if ($address->type == "shipping"): ?>
                            <p>Shipping</p>
                            <?php endif; ?>

                            <?php if ($address->type == "billing"): ?>
                                <p>Billing</p>
                            <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-3 text-center">
                            <p>
                                <?php if ($address->default_shipping): ?>
                            <p>Default Shipping</p>
                            <?php endif; ?>

                            <?php if ($address->default_billing): ?>
                                <p>Default Billing</p>
                            <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <p>
                                <?= Html::a('Edit', null, ['id' => "address_edit", 'data-action' => "edit", 'data-url' => StoreUrl::to("account/addresses"), 'data-aid' => $address->address_id, 'class' => 'btn btn-primary left-btn-space']); ?>
                                <?= Html::a('Remove', null, ['id' => "address_delete", 'data-action' => "delete", 'data-url' => StoreUrl::to("account/addresses"), 'data-aid' => $address->address_id, 'class' => 'btn btn-danger']); ?>
                            </p>
                        </div>
                    </div>
                    <br><br>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row m-t--sm">
                    <div class="col-md-4">
                        <p>
                            No Addresses Found
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

