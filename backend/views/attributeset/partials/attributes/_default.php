<?php
use common\components\CurrentStore;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;
use yii\helpers\Html;
?>

<div class="col-md-4 grid-item">
    <div class="panel panel__ui attribute__ui default-attribute">
        <div class="panel-body">
            <div class="panel-action">
                <?php if (!$attribute->is_default && $attribute->store_id == CurrentStore::getStoreId()): ?>
                    <?php echo Html::a('<i class="material-icons">more_horiz</i>', ['update', 'id' => $attribute->id], ['class' => 'text-muted pull-right']); ?>
                <?php else: ?>
                    <i class="material-icons pull-right">lock</i>
                <?php endif; ?>
            </div>
            <span class="attribute-set-cat"><?php echo CatalogAttributeSetCategory::findOne($attribute->category_id)->label; ?></span>

            <div class="clearfix">
                <h4 class="attribute-name pull-left"><?php echo $attribute->label; ?></h4>
                <span class="attribute-type label label-attribute pull-right"><?php echo CatalogAttributeType::findOne($attribute->type_id)->type; ?></span>
            </div>
        </div>
    </div>
    <input type="hidden" name="attribute_order[]" data-value="<?= $attribute->id; ?>" />
</div>