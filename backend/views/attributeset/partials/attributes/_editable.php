<?php
use yii\helpers\Html;
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;
?>

<div class="col-md-4 grid-item">
    <div class="panel panel__ui attribute__ui">
        <div class="panel-body">
            <div class="panel-action">
                <?php if (CurrentStore::isNone() || CatalogAttributeSet::findOne($attributeSetId)->store_id == CurrentStore::getStoreId()): ?>
                    <div class="">
                        <?php echo Html::a('Remove', [
                            'select',
                            'id'     => $attributeSetId,
                            'aid'    => $attribute->id,
                            'action' => 'remove',
                        ], ['class'  => 'btn btn-text']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <span class="attribute-set-cat"><?php echo CatalogAttributeSetCategory::findOne(CatalogAttribute::findOne($attribute->id)->category_id)->label; ?></span>

            <div class="clearfix">
                <h4 class="attribute-name pull-left"><?php echo $attribute->label; ?></h4>
                <span class="attribute-type label label-attribute pull-right"><?php echo CatalogAttributeType::findOne(CatalogAttribute::findOne($attribute->id)->type_id)->type; ?></span>
            </div>
        </div>
    </div>
    <input type="hidden" name="attribute_order[]" data-value="<?= $attribute->id; ?>" />
</div>