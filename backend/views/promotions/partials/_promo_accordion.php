<?php

use yii\helpers\Html;
use common\models\promotion\PromotionDiscount;
use common\components\CurrentStore;
use common\models\promotion\PromotionDiscountCondition;
use common\models\promotion\PromotionBuyxgety;
use yii\helpers\Url;

$discounts = PromotionDiscount::findAll(['promotion_id' => $promotion->id]);
$buyxgetys = PromotionBuyxgety::findAll(['promotion_id' => $promotion->id]);

?>

<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="promo-<?= $promotion->id ?>">
        <div class="panel-title">
            <div class="row">
                <div class="col-md-3">
                    <h4><?= $promotion->label ?></h4>
                </div>
                <div class="col-md-9 text-right">
                    <small class="text-muted">
                        Valid from <b class="text-primary"><?= date('m/d/Y', $promotion->starts_at) ?></b> to <b class="text-primary"><?= date('m/d/Y', $promotion->ends_at) ?></b>
                    </small>
                    &nbsp;|&nbsp;
                  <!-- <?php /*if ($discounts || $buyxgetys): */?>
                        <?/*= Html::a('Discounts', [
                            "#collapse-$promotion->id",
                        ], [
                            'class' => 'btn btn-secondary',
                            'data-toggle' => 'collapse',
                            'data-parent' => '#accordion'
                        ]);
                        */?>
                    <?php /*endif; */?>-->

                    <?= Html::a('Add Discount', [
                        'add-promo',
                        'id' => $promotion->id,
                    ],
                        ['class' => 'btn btn-secondary']);
                    ?>
                    <?= Html::a(isset($enabled) ? 'Disable' : ($promotion->store_id == CurrentStore::getStoreId() ? 'Enable' : 'Clone'), [
                        'enable-promo',
                        'pid'    => $promotion->id,
                        'action' => isset($enabled) ? 'disable' : 'enable',
                    ], ['class'  => 'btn btn-primary']);
                    ?>
                    <?= Html::a('Update', [
                        "promotions/update/$promotion->id",
                    ], [
                        'class' => 'btn btn-primary'
                    ]);
                    ?>
                    <a href="javascript: if(confirm('Are you sure you wih to delete <?= $promotion->label ?>, ID# <?= $promotion->id ?>?')) {
                              window.location='<?= Url::to(['/promotions/delete', 'id' => $promotion->id]) ?>';}"
                       class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div id="<!--collapse---><?= $promotion->id ?>" class="<!--panel-collapse collapse-->" role="tabpanel" aria-labelledby="promo-<?= $promotion->id ?>">

        <?php foreach($discounts as $discount):?>
            <?php $conditions = PromotionDiscountCondition::find()->where(['discount_id' => $discount->id])->all(); ?>
            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <h3><?php if ($discount) {
                                echo $discount->label;
                            } ?></h3>
                        <br>
                        <h5><?php if ($discount) {
                                echo $discount->type == 'percent' ? "$discount->amount%" : '$' . number_format($discount->amount, 2);
                            } ?> Discount</h5>
                    </td>
                    <td>
                        <h3>Rules</h3><br/>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Condition</th>
                                <th>Key</th>
                                <th>Operation</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ($conditions): ?>
                                <?php foreach ($conditions as $index => $condition): ?>
                                    <tr>
                                        <td><b><?= $index ? strtoupper($condition->condition) : 'WHERE'; ?></b></td>
                                        <td><?= strtoupper($condition->key); ?></td>
                                        <td><b><?= strtoupper($condition->operation); ?></b></td>
                                        <td><?= $condition->value; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No conditions yet.</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if ($promotion->store_id == CurrentStore::getStoreId()): ?>
                            <?php if ($discount): ?>
                            <br>
                                <a href="javascript: if(confirm('Are you sure you wish to delete <?= $discount->label ?>, ID# <?= $discount->id ?>?')) {
                              window.location='<?= Url::to(['/promotions/delete-discount', 'id' => $promotion->id, 'idd' => $discount->id]) ?>';}"
                                   class="btn btn-danger pull-right">Delete</a>
                                <a href="<?= Url::to(['/promotions/discounts', 'id' => $promotion->id, 'idd' => $discount->id]) ?>"
                                   class="btn btn-secondary pull-right btn-space">Update Rules</a>
                            <?php else: ?>
                                <a href="<?= Url::to(['/promotions/discounts', 'id' => $promotion->id]) ?>"
                                   class="btn btn-secondary pull-right">Add Rules</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php endforeach; ?>

    <?php foreach($buyxgetys as $buyxgety):?>
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <h3><?= $buyxgety->label ?></h3>
                        <h5>Buy X Get Y</h5>
                    </td>
                    <td>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Buy X</th>
                                    <th>Get Y</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td><b><?= $buyxgety->x_amount ?> | <?= $buyxgety->x_sku ?></b></td>
                                        <td><b><?= $buyxgety->y_amount ?> | <?= $buyxgety->y_sku ?></b></td>
                                    </tr>
                            </tbody>
                        </table>
                       <?php if ($promotion->store_id == CurrentStore::getStoreId()): ?>
                       <br>
                                <a href="javascript: if(confirm('Are you sure you wish to delete <?= $buyxgety->label ?>, ID# <?= $buyxgety->id ?>?')) {
                              window.location='<?= Url::to(['/promotions/delete-buy-x-get-y', 'id' => $promotion->id, 'xy' => $buyxgety->id]) ?>';}"
                                   class="btn btn-danger pull-right">Delete</a>
                                <a href="<?= Url::to(['/promotions/buy-x-get-y', 'id' => $promotion->id, 'xy' => $buyxgety->id]) ?>"
                                   class="btn btn-secondary pull-right btn-space">Update</a>
                       <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
    </div>
</div>