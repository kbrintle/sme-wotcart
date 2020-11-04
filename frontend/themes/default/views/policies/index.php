<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\StoreUrl;

//echo "<pre>";
//print_r($financing);

?>
<section class="bg-lightgray content-pad">
    <div class="pad-sm">
        <div class="container">

            <h2>Store Policies/Benefits</h2>
            <div class="row pad-sm">
                <div class="col-md-12">
                    <?php if(isset($policies->name_1) && isset($policies->policy_details_1)):?>
                        <div class="policy-block">
                            <h3><?php echo $policies->name_1; ?></h3>
                            <p><?php echo $policies->policy_details_1; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($policies->name_2) && isset($policies->policy_details_2)):?>
                        <div class="policy-block">
                            <h3><?php echo $policies->name_2; ?></h3>
                            <p><?php echo $policies->policy_details_2; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($policies->name_3) && isset($policies->policy_details_3)):?>
                        <div class="policy-block">
                            <h3><?php echo $policies->name_3; ?></h3>
                            <p><?php echo $policies->policy_details_3; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($policies->name_4) && isset($policies->policy_details_4)):?>
                        <div class="policy-block">
                            <h3><?php echo $policies->name_4; ?></h3>
                            <p><?php echo $policies->policy_details_4; ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($policies->general_policy) && isset($policies->general_policy_details)):?>
                        <div class="policy-block">
                            <h3><?php echo $policies->general_policy; ?></h3>
                            <p><?php echo $policies->general_policy_details; ?></p>
                        </div>
                    <?php endif; ?>

            </div>
        </div>
    </div>
</section>