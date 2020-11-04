<?php
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;

?>

<div class="row action-row">
    <div class="col-md-12">
        <h3 class="">Selected Attributes</h3>
    </div>
</div>
<div class="row">
    <?php if( empty($selectedAttributes) ): ?>
        <table class="table">
            <tbody>
            <tr>
                <td><i class="material-icons">info</i></td>
                <td>
                    <b>It looks like you haven't selected any Attributes yet.</b>
                    <br />To add an attribute to this set, click the 'Select' button next to an attribute in the 'Available Attributes' section.
                </td>
            </tr>
            </tbody>
        </table>
    <?php else: ?>
        <?php foreach ($selectedAttributes as $selectedAttribute): ?>
            <div class="col-md-4">
                <div class="panel panel__ui attribute__ui">
                    <div class="panel-body">
                        <div class="panel-action">
                            <?php if (CurrentStore::isNone() || CatalogAttributeSet::findOne($attributeSetId)->store_id == CurrentStore::getStoreId()): ?>
                                <div class="">
                                    <?php echo Html::a('Remove', [
                                        'select',
                                        'id'     => $attributeSetId,
                                        'aid'    => $selectedAttribute->id,
                                        'action' => 'remove',
                                    ], ['class'  => 'btn btn-text']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="attribute-set-cat"><?php echo CatalogAttributeSetCategory::findOne(CatalogAttribute::findOne($selectedAttribute->id)->category_id)->label; ?></span>

                        <div class="clearfix">
                            <h4 class="attribute-name pull-left"><?php echo $selectedAttribute->label; ?></h4>
                            <span class="attribute-type label label-attribute pull-right"><?php echo CatalogAttributeType::findOne(CatalogAttribute::findOne($selectedAttribute->id)->type_id)->type; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>