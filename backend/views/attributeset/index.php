<?php

use yii\helpers\Html;
use common\models\catalog\CatalogAttributeSetAttribute;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogAttributeSetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attribute Sets';
?>

<div class="container-fluid pad-xs">
    <div class="attribute-index">

        <?php if (empty($attributeSets)): ?>
            <div class="empty-state text-center">
<!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have any Attribute Sets yet</h3>
                <p>To get started, click the 'New Attribute Set' button below.</p>
                <?php echo Html::a('New Attribute Set', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <?php echo Html::a('New Attribute Set', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
            <br /><br />

            <div class="row">
                <?php foreach ($attributeSets as $attributeSet): ?>
                    <div class="col-md-4">
                        <div class="panel panel__ui attribute-set__ui">
                            <div class="panel-heading">
                                <?php if (!$attributeSet->is_default): ?>
                                    <?php echo Html::a('<i class="material-icons">settings</i>', ['update', 'id' => $attributeSet->id], ['class' => 'text-muted pull-right']); ?>
                                <?php endif; ?>
                                <h4><?php echo $attributeSet->label; ?> Set</h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4><?php echo count(CatalogAttributeSetAttribute::findAll(['set_id' => $attributeSet->id])) ; ?> Attributes</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo Html::a('View Attributes <i class="material-icons">keyboard_arrow_right</i>', ['attributes', 'id' => $attributeSet->id], ['class' => 'btn btn-text']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>