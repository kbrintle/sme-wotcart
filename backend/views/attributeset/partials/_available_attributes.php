<?php
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;
use yii\helpers\Html;

?>
<div class="row action-row">
    <div class="col-md-12 clearfix">
        <h3 class="pull-left">Available Attributes</h3>
    </div>
</div>
<div class="row">
    <?php if( empty($availableAttributes) ): ?>
        <table class="table">
            <tbody>
            <tr>
                <td><i class="material-icons">info</i></td>
                <td>
                    <b>No Attributes are available.</b>
                    <br />Either you're out of attributes or haven't created any yet.
                </td>
            </tr>
            </tbody>
        </table>
    <?php else: ?>
        <?php foreach($availableAttributes as $availableAttribute): ?>
            <div class="col-md-12">
                <div class="panel panel__ui attribute__ui">
                    <div class="panel-body">
                        <div class="panel-action">
                            <?php if (CurrentStore::isNone() || CatalogAttributeSet::findOne($attributeSetId)->store_id == CurrentStore::getStoreId()): ?>
                                <div class="">
                                    <?php echo Html::a('<i class="material-icons">add</i>', [
                                        'select',
                                        'id'     => $attributeSetId,
                                        'aid'    => $availableAttribute->id,
                                        'action' => 'select',
                                    ], ['class'  => '']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <span class="attribute-set-cat"><?php echo CatalogAttributeSetCategory::findOne(CatalogAttribute::findOne($availableAttribute->id)->category_id)->label; ?></span>

                        <div class="clearfix">
                            <h4 class="attribute-name pull-left"><?php echo $availableAttribute->label; ?></h4>
                            <span class="attribute-type label label-attribute pull-right"><?php echo CatalogAttributeType::findOne(CatalogAttribute::findOne($availableAttribute->id)->type_id)->type; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>