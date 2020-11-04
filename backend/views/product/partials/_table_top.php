<?php

use yii\helpers\Html;

?>
<!-- $scope.bulk_update REQUIRES an ng-value object with keys `active` and `value` at a minimum
    ie: <option ng-value={key:'sample key',value:'sample value'}>SAMPLE OPTION</option>
     `key` will be referenced in the ProductGrid model (bulkUpdate_`key`) !-->

<?php $current_store = \common\components\CurrentStore::getStore(); ?>

<div class="alert alert-success"
     ng-show="success_message">
    <a class="close" aria-label="close"
       ng-click="success_message=null">&times;</a>
    {{success_message}}
</div>

<div class="bulk_status_options pad-btm-sm">
    <select class="form-control bulk_status_updater display-inline"
        ng-model="bulk_update">
        <option value="">Bulk Actions</option>
        <option ng-value={key:'active',value:false}>Set Inactive</option>
        <option ng-value={key:'active',value:true}>Set Active</option>
        <?php if( is_null($current_store) ): ?>
            <option ng-value="{key:'delete',value:true}">Delete</option>
        <?php endif; ?>
    </select>
    <a class="btn btn-secondary display-inline"
        ng-if="bulk_update
                && checkedProducts().length > 0"
        ng-click="updateBulk()">Apply</a>
    <?php echo Html::a('Reset Filter', [''], ['class' => 'btn btn-primary']); ?>

</div>