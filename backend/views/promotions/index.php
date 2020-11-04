<?php

use yii\helpers\Html;
use common\components\CurrentStore;

/* @var $this yii\web\View */
/* @var $searchModel common\models\sales\search\SalesOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Targeted Promotions';

?>
<style>
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        color: #555;
        cursor: default;
        background-color: transparent;
        border: none;
        border-bottom-color: transparent;
    }

    table.table tbody tr td {
        width: 200px;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 0px;
    }

</style>
<div class="contianer-fluid pad-xs">
    <div class="promotion-index">
        <div class="row action-row">
            <div class="col-md-12">
                <?= Html::a('Create Promotion', ['create'], ['class' => 'btn btn-primary pull-right']) ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php if (CurrentStore::isNone()): ?>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#all_store_promotions" aria-controls="enabled" role="tab" data-toggle="tab">All Store Promotions<span class="badge"></span></a>
                    </li>
                    <li role="presentation">
                        <a href="#individual_store_promotions" aria-controls="available" role="tab" data-toggle="tab">Individual Store Promotions<span class="badge"></span></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <br>
                    <div role="tabpanel" class="tab-pane" id="individual_store_promotions">
                        <?= $this->render('partials/_admin_index', [
                            'searchModel'  => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]) ?>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="all_store_promotions">
                        <?= $this->render('partials/_store_index', [
                            'enabled' => $enabled,
                            'available' => $available,
                            'badges' => $badges
                        ]) ?>
                    </div>
                </div>

            <?php else: ?>
                <?= $this->render('partials/_store_index', [
                    'enabled'   => $enabled,
                    'available' => $available,
                    'badges'    => $badges
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>