<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\store\search\StoreCommercialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Commercial';
?>
<div class="container-fluid pad-xs">
    <div class="commercials-index">

        <?php if (empty($commercials)): ?>
            <div class="empty-state text-center">
                <!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have a Commercial yet</h3>
                <p>To get started, click the 'Add Commercial' button below.</p>
                <?php echo Html::a('Add Commercial', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="clearfix pad-xs">
                        <?php echo (sizeof($commercials) < 5) ? Html::a('Add Commercial', ['create'], ['class' => 'pull-right  btn btn-primary btn-lg']) : ''; ?>
                    </div>

                    <div class="panel panel__ui">
                        <div class="panel-heading">
                            <h4>Active Commercials</h4>
                        </div>
                        <?php foreach($commercials as $commercial): ?>
                        <div class="panel-body">
                            <div class="row pad-top">
                                <div class="col-md-8 col-md-offset-2 text-center">
                                    <?= $commercial->url; ?>
                                    <div class="row pad-sm">
                                        <div class="col-md-12">
                                            <?php echo Html::a('Update', ['update', 'id' => $commercial->id], ['class' => 'btn btn-secondary']); ?>
                                            <?php echo Html::a('Remove', ['delete', 'id' => $commercial->id], ['class' => 'btn btn-secondary']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>