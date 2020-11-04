<?php
use yii\helpers\Url;
?>


<div class="locations--store-details">
    <div class="row">
        <div class="col-sm-7">
            <h2 class="locations--store-title"><?php echo $location->name ?></h2>
            <div class="locations--store-address">
                <span class="locations--store-address-line1"><?php echo $location->address ?></span>
                <span class="locations--store-address-line2"><?php echo $location->alt_address ?></span>
                <span class="locations--store-city"><?php echo $location->city ?>, <?php echo $location->state ?> <?php echo $location->zipcode ?></span>
            </div>
            <span class="locations--store-phone"><?php echo $location->phone ?></span>
            <div class="locations--store-hours">
                <div class="row">
                    <div class="col-sm-12">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="locations--store-links">
                <a href="#" class="email-store btn btn-default btn-responsive">Email Store</a>
                <a href="#" class="directions-store btn btn-default btn-responsive">Get Directions</a>
                <a href="<?php Url::toRoute(['location/detail', 'slug' => $location->stub]);?>" class="view-store btn btn-primary btn-responsive">View Details</a>
            </div>
        </div>
    </div>
</div>