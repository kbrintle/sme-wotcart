<?php

use yii\helpers\Html;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeType;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeSetAttribute;
use common\models\catalog\CatalogAttributeSetCategory;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogAttributeSetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Attribute Sets';
?>
<div class="container-fluid pad-xs">
    <div class="attribute-index">
        <div class="row action-row">
            <div class="col-md-12 clearfix">
                <?= Html::a('Save', ['index'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $this->render('partials/_available_attributes', [
                        'availableAttributes'   => $availableAttributes,
                        'attributeSetId'        => $attributeSetId
                    ]); ?>
            </div>
            <div class="col-md-8">

                <?php //echo $this->render('partials/_base_attributes', [
//                        'defaultAttributes' => $defaultAttributes,
//                        'attributeSetId'    => $attributeSetId
//                    ]); ?>

                <?php //echo $this->render('partials/_selected_attributes', [
//                        'selectedAttributes'    => $selectedAttributes,
//                        'attributeSetId'        => $attributeSetId
//                    ]); ?>


                <?= $this->render('partials/_all_attributes', [
                        'all_attributes'    => $allAttributes,
                        'attributeSetId'    => $attributeSetId
                    ]); ?>

            </div>
        </div>
    </div>
</div>