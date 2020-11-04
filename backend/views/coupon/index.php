<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\store\search\StoreCouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coupons';
?>
<div class="container-fluid pad-xs">
    <div class="commercials-index">

        <?php if (empty($coupons)): ?>
            <div class="empty-state text-center">
                <!--                <i class="material-icons">info</i>-->
                <h3>It looks like you don't have any Coupons yet</h3>
                <p>To get started, click the 'Add Coupon' button below.</p>
                <?php echo Html::a('Add Coupon', ['create'], ['class' => 'btn btn-primary btn-lg']); ?>
            </div>
        <?php else: ?>
            <?php if (count($coupons) < 4): ?>
                <div class="row action-row">
                    <div class="col-md-12">
                        <?php echo Html::a('Add Coupon', ['create'], ['class' => 'btn btn-primary pull-right']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel__ui">
                        <div class="panel-heading">
                            <h4>Active Coupons</h4>
                        </div>
                        <div class="panel-body">
                            <?php foreach ($coupons as $coupon): ?>
                                <div class="row">
                                    <div class="col-md-9">
                                        <img src="<?= Url::to('@frontendurl/uploads/coupons/') . $coupon->image; ?>" />
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo Html::a('Update', ['update', 'id' => $coupon->id], ['class' => 'btn btn-secondary']); ?>
                                        <?php echo Html::a('Remove', ['delete', 'id' => $coupon->id], ['class' => 'btn btn-secondary']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
