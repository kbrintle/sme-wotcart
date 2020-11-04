<?php

use common\models\customer\CustomerReward;
use app\components\StoreUrl;

$rewardPoints = CustomerReward::getUsablePoints($model->customer_user_id);

if ($rewardPoints > 0): ?>

<style>

    .reward-label .error{
        display: block;
        margin-top: 5px;
        margin-bottom: 10px;
        top: -3px;
        right: 0;
        color: #AB0006;
        font-size: 10px;
        font-family: "Work Sans", sans-serif;
    }

</style>
    <div class="checkout-order-payment">
        <div class="panel panel__ui">
            <div class="panel-heading panel__ui-heading clearfix">
                <h3 class="panel__ui-heading-ttl pull-left">Reward Points</h3>
            </div>
            <div class="panel-body panel__ui-body">
                <h4>You have <?= $rewardPoints; ?> reward
                    <?= ($rewardPoints > 1 ? "points" : "point") ?>
                </h4>Each point in worth 1 cent
                <br><br>
                <table class="table">
                    <tbody>
                <tr id="reward">
                    <td class="reward-label">
                        <input type="text" id="reward-points" class="form-control"
                               placeholder="Points to Apply" autocomplete="no">
                        <div class='reward-error'></div>
                    </td>
                    <td class="reward-value">
                        <a id="reward-apply" class="btn btn-default"
                           data-action="<?php echo StoreUrl::to('cart/reward-points'); ?>">Apply</a>
                    </td>
                </tr></tbody></table>
              <!--  --><?/*= $form->field($model, 'reward_points')->textInput()->input('reward_points', ['placeholder' => "Points to Apply"])->label(false); */?>
            </div>
        </div>
    </div>
<?php endif; ?>