<?php

use yii\helpers\Html;
use common\models\catalog\CatalogAttributeType;
use common\models\catalog\CatalogAttributeSetCategory;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogAttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attributes';
?>



<div class="container-fluid pad-xs">
    <div class="attribute-index">
        <?php if (empty($attributes)): ?>
            <div class="empty-state text-center">
                <h3>It looks like you don't have any Attributes yet</h3>
                <p>To get started, click the 'New Attribute' button below.</p>
                <?php echo Html::a('New Attribute', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::a('New Attribute', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
                </div>
            </div>
            <div class="row">
                <?php foreach ($attributes as $attribute): ?>
                    <div class="col-md-3">
                        <div class="panel panel__ui attribute__ui">
                            <div class="panel-body clearfix">
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
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>