<?php

use app\components\StoreUrl;

?>

<div class="account-nav">
    <h3 class="nav-menu-heading">My Account</h3>
    <ul class="nav nav-tabs">
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'overview') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/overview"); ?>">Account Overview</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'information') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/information"); ?>">Account Information</a>
        </li>
        <li role="navigation" class="">
            <a href="<?= StoreUrl::to("pay-bill"); ?>">Pay Your Invoice</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'update-password') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/update-password"); ?>">Update Password</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'list') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("favorites/list"); ?>">My Favorites</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'addresses') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/addresses"); ?>">My Addresses</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'orders') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/orders"); ?>">My Orders</a>
        </li>
        <li role="navigation" class="<?= (Yii::$app->controller->action->id == 'orders') ? 'active' : '' ?>">
            <a href="<?= StoreUrl::to("account/clinic-spotlight"); ?>">Clinic Spotlight</a>
        </li>
        <li role="navigation" class="">
            <a href="<?= StoreUrl::to("account/logout"); ?>">Log Out</a>
        </li>
    </ul>
</div>