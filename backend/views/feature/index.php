<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $searchModel common\models\catalog\search\CatalogProductFeatureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog Product Features';
//$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="container-fluid pad-xs">
        <div class="catalog-product-feature-index">

            <?php if (empty($features)): ?>
                <div class="empty-state text-center">
                    <!--                <i class="material-icons">info</i>-->
                    <h3>It looks like you don't have any Features yet</h3>
                    <p>To get started, click the 'New Feature' button below.</p>
                    <?php echo Html::a('New Feature', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
                </div>
            <?php else: ?>
                <div class="row action-row">
                    <div class="col-md-12">
                        <?php echo Html::a('New Feature', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($features as $feature): ?>
                        <div class="col-md-4">
                            <div class="panel panel__ui feature__ui">
                                <div class="panel-body">
                                    <div class="panel-action">
                                        <?php if ($feature->store_id == CurrentStore::getStoreId()): ?>
                                            <?php echo Html::a('<i class="material-icons">more_horiz</i>', ['update', 'id' => $feature->id], ['class' => ' pull-right']); ?>
                                        <?php else: ?>
                                            <i class="material-icons pull-right">lock</i>
                                        <?php endif; ?>
                                    </div>
                                    <span class="feature-cat">
                                        Feature
                                    </span>
                                    <h3 class="feature-name"><?php echo $feature->name; ?></h3>
                                    <p class="feature-description"><?php echo $feature->description; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
