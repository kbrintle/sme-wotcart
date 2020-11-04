<?php

use  \common\models\customer\CustomerAddress;

$customerAddresses = CustomerAddress::find()->Where(['customer_id' => $model->id])->orderBy(['address_id' => SORT_ASC])->all();
?>

<style>
    .address-select {
        cursor: pointer;
    }

    .selected {
        background-color: #00ab50d4;
    }

    .address-select:hover {
        background-color: #00ab504a;
    }

    .address-select:active {
        background-color: #00ab504a;
    }

    .address-select:focus {
        background-color: #00ab504a;
    }

    @keyframes flash {
        0% {
            background-color: transparent;
        }
        50% {
            background-color: #00ab504a;
        }
        100% {
            background-color: transparent;
        }
    }

    @-webkit-keyframes flash {
        0% {
            background-color: transparent;
        }
        50% {
            background-color: #00ab504a;
        }
        100% {
            background-color: transparent;
        }
    }

    @-moz-keyframes flash {
        0% {
            background-color: transparent;
        }
        50% {
            background-color: #00ab504a;
        }
        100% {
            background-color: transparent;
        }
    }

    @-ms-keyframes flash {
        0% {
            background-color: transparent;
        }
        50% {
            background-color: #00ab504a;
        }
        100% {
            background-color: transparent;
        }
    }

    .glyphicon-remove {
        padding: 10px;
        cursor: pointer;
    }

    .modal-dialog {
        top: 36%;
    }
</style>
<div id="addressTab">
    <div class="col-md-6">
        <div id="left-address-column">
            <?php if (!empty($customerAddresses)): ?>
                <?php $new = false; ?>
            <?php else: ?>
                <?php
                $addressModel = new CustomerAddress();
                $addressModel->customer_id = $model->id;
                $addressModel->save(false);
                $customerAddresses[] = $addressModel;
                $new = true; ?>
            <?php endif; ?>
            <?php $address = $customerAddresses[0] ?>
            <?php foreach ($customerAddresses as $key => $customerAddress): ?>
                <div>
                    <?= Yii::$app->controller->renderPartial('partials/_addressinfo',
                        ['address' => $customerAddress, 'key' => $key, 'new' => $new]); ?>
                </div>
            <?php endforeach ?>

        </div>
        <a id="address-create" url="/admin/customer/get-address-form"
           class="btn btn-primary pull-right">
            New Address
        </a>
    </div>
    <div id="updatePanel" class="col-md-6">
        <div class="catalog-category-update">
            <div class="container-fluid">
                <div class="panel panel__ui">
                    <div id="background-flash" class="panel-body">
                        <div id="address-form-container">
                            <?= Yii::$app->controller->renderPartial('partials/_addressform',
                                ['address' => $address]); ?>
                        </div>
                        <a id="address-save" url="/admin/customer/ajax-address"
                           class="btn btn-primary pull-right">
                            Update
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="delete-address" class="fade modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this address?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="button" id="model-delete-address" data-dismiss="modal" class="btn">Delete</button>
            </div>
        </div>
    </div>
</div>


