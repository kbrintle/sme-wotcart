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
        <div class="locations pad-sm">
            <div class="row pad-btm-sm">
                <div class="col-md-12">
                    <div class="locations--results">
                        <?php //@todo echo zipcode user inputs here ?>
<!--                        <h3>8 Results for Zipcode 28411</h3>-->
                    </div>
                    <?php //@todo i was going to add another zip search on the locations results page ?>
<!--                    <div class="locations--locate pull-right">-->
<!--                            --><?php
//
//                            $model = new ZipLookupForm();
//                            $form = ActiveForm::begin(['id' => 'zip-form', 'action'=>'/national/store/find']); ?>
<!--                            <?//= $form->field($model, 'zip')->textInput()->input('zip', ['placeholder' => "Enter Your Zipcode", 'maxlength' => '5'])->label(false); ?> -->
<!---->
<!--                            <?//= Html::submitButton('Find Stores', ['data-btn'=>'check-zip', 'class' => 'btn btn-primary', 'name' => 'contact-button', 'data-dismiss'=>'modal']) ?> -->
<!---->
<!---->
<!--                            --><?php //ActiveForm::end(); ?>
<!--                    </div>-->

                    <div id="map" class="locations-singleview-map"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php foreach($locations as $location):?>
                    <div class="locations--single">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="locations--store-img">
<!--                                    --><?php //echo Html::img(Assets::themeResource('stores/store_locations_img.jpg'), ['alt'=>'America\'s Mattress', 'class'=>'img-responsive']);?>
                                </div>
                            </div>
                            <div class="col-sm-10">
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
                                                        <?php echo Yii::$app->controller->renderPartial('_hours', ['location'=>$location]); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="locations--store-links">
<!--                                                <button type="button" class=" email-store btn btn-default btn-lg btn-fullwidth btn-pad-btm" data-toggle="modal" data-target="#emailStore">-->
<!--                                                    Email Store-->
<!--                                                </button>-->
                                                <a href="https://www.google.com/maps?saddr=My+Location&daddr=<?php echo $location->getGoogleDestinationAddress() ?>"
                                                   class="directions-store btn btn-default btn-xl btn-fullwidth btn-pad-btm"
                                                   target="_blank"
                                                >Get Directions</a>
                                                <a href="<?php echo StoreUrl::to("locations/detail/".$location->slug);?>" class="view-store btn btn-primary btn-xl btn-responsive">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<?php echo $this->render('modals/_email_modal', ['model'=>$model]) ?>

<?php
foreach($locations as $location) {
    $markers []= [
        $location->name,
        $location->latitude,
        $location->longtitude
    ];
}
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPShOWFoFCJ8DAWpDRPMqM4igYOePL_DI&callback=initMap"></script>
<script>
    var positions = <?php echo json_encode($markers) ?>;

    console.log(positions);
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: <?php echo $location->latitude ?>, lng: <?php echo $location->longtitude ?>}
        });

        setMarkers(map);
    }


    function setMarkers(map) {
//        var image = {
//            url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
//            // This marker is 20 pixels wide by 32 pixels high.
//            size: new google.maps.Size(20, 32),
//            // The origin for this image is (0, 0).
//            origin: new google.maps.Point(0, 0),
//            // The anchor for this image is the base of the flagpole at (0, 32).
//            anchor: new google.maps.Point(0, 32)
//        };

        for (var i = 0; i < positions.length; i++) {
            var store = positions[i];

            console.log(store[1]);
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(store[1]), lng: parseFloat(store[2])},
                map: map,
                title: store[0]
            });
        }
    }
</script>

