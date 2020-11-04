<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\components\StoreUrl;
use frontend\models\ZipLookupForm;
use yii\bootstrap\ActiveForm;
use frontend\components\Assets;

?>
<section class="bg-lightgray content-pad">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="locations-singleview pad-sm">
                    <div class="row">
                        <div class="col-sm-8">
                            <div id="gmap_container" class="locations-singleview-map"></div>
                        </div>
                        <div class="col-sm-4">

                                <div class="locations--single">
                                    <div class="locations--store-details">
                                        <h2 class="locations--store-title"><?php echo $location->name ?></h2>
                                        <div class="row pad-btm">
                                            <div class="locations--store-address">
                                                <div class="col-md-2">
                                                    <i class="material-icons">location_on</i>
                                                </div>
                                                <div class="col-md-10">
                                                    <span class="locations--store-address-line1"><?php echo $location->address ?></span>
                                                    <span class="locations--store-address-line2"><?php echo $location->alt_address ?></span>
                                                    <span class="locations--store-city"><?php echo $location->city ?>, <?php echo $location->state ?> <?php echo $location->zipcode ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pad-btm">
                                            <div class="locations--store-phone">
                                                <div class="col-md-2">
                                                    <i class="material-icons">contact_phone</i>
                                                </div>

                                                <div class="col-md-10">
                                                    <?php echo $location->phone ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pad-btm">
                                            <div class="locations--store-hours">
                                                <div class="col-sm-12">
                                                    <?php echo Yii::$app->controller->renderPartial('_hours', ['location'=>$location]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="locations-singleview-links">
                                            <!-- Button trigger modal -->
                                            <button type="button" class=" email-store btn btn-default btn-lg btn-responsive btn-pad-btm" data-toggle="modal" data-target="#emailStore">
                                                Email Store
                                            </button>
                                            <a href="https://www.google.com/maps?saddr=My+Location&daddr=<?php echo $location->getGoogleDestinationAddress() ?>"  target="_blank" class="directions-store btn btn-primary btn-lg btn-fullwidth">Get Directions</a>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="locations-singleview-comments">
                                <?php //@todo add comment section here ?>

                                <!-- Add Comments -->
                            </div>
                        </div>
<!--                        <div class="col-sm-4">-->
<!--                            <div class="locations-singleview-store-img">-->
<!--                                <!-- Allow store to change this image -->
<!--                                --><?php //echo Html::img(Assets::themeResource('stores/store_locations_img.jpg'), ['alt'=>'America\'s Mattress', 'class'=>'img-responsive']);?>
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php echo $this->render('modals/_email_modal', ['model'=>$model]) ?>



<script>
    function init_map(lat, long){
        console.log(lat, long);
        if( typeof lat !== 'undefined'
            && typeof long !== 'undefined' ){
            var location = {lat: lat, lng: long};
            var map = new google.maps.Map(
                document.getElementById("gmap_container"),
                {
                    zoom: 15,
                    center: location
                });
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    };
    function gmaps_callback(){
        init_map(<?php echo $location->latitude; ?>, <?php echo $location->longtitude; ?>);
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPShOWFoFCJ8DAWpDRPMqM4igYOePL_DI&callback=gmaps_callback"></script>

