<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\core\Store;
use common\components\CurrentStore;
use backend\components\CurrentUser;
?>

<div class="panel page-sub-heading white-bg">

    <div class="panel-body">
        <div class="col-xs-8">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <?php if(CurrentUser::isAdmin()):?>
            <div class="col-xs-2 col-md-2">
            <?php echo Html::a('Refresh Products', '/admin/product/refresh',[
                'id'=>'cache-refresh',
                'class'=>'btn btn-default btn-small'
            ]); ?>
            </div>
        <?php endif; ?>
        <?php if(CurrentUser::isAdmin() || CurrentUser::isStoreAdmin()):?>
        <div class="col-xs-2 col-md-2">
            <?php echo Html::dropDownList('store_id', CurrentStore::getStoreId(), Store::getStoreList(), [
                'id'=>'store-selector',
                'class'=>'selectpicker'
            ]); ?>
        </div>
        <?php endif; ?>
    </div>

</div>