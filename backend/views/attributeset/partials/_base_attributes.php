<?php
use common\components\CurrentStore;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;
use yii\helpers\Html;
?>

<div class="row action-row">
    <div class="col-md-12">
        <h3 class="">Base Attributes</h3>
    </div>
</div>
<div class="row">
    <?php if( empty($defaultAttributes) ): ?>
        <table class="table">
            <tbody>
            <tr>
                <td><i class="material-icons">info</i></td>
                <td>
                    <b>It looks like you don't have any Default Attributes yet.</b>
                    <br />Default attributes are created during system setup. Please contact an administrator to create defaults.
                </td>
            </tr>
            </tbody>
        </table>
    <?php else: ?>
        <?php foreach ($defaultAttributes as $defaultAttribute): ?>
            <div class="col-md-4">
                <div class="panel panel__ui attribute__ui default-attribute">
                    <div class="panel-body">
                        <div class="panel-action">
                            <?php if (!$defaultAttribute->is_default && $defaultAttribute->store_id == CurrentStore::getStoreId()): ?>
                                <?php echo Html::a('<i class="material-icons">more_horiz</i>', ['update', 'id' => $defaultAttribute->id], ['class' => 'text-muted pull-right']); ?>
                            <?php else: ?>
                                <i class="material-icons pull-right">lock</i>
                            <?php endif; ?>
                        </div>
                        <span class="attribute-set-cat"><?php echo CatalogAttributeSetCategory::findOne($defaultAttribute->category_id)->label; ?></span>

                        <div class="clearfix">
                            <h4 class="attribute-name pull-left"><?php echo $defaultAttribute->label; ?></h4>
                            <span class="attribute-type label label-attribute pull-right"><?php echo CatalogAttributeType::findOne($defaultAttribute->type_id)->type; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>