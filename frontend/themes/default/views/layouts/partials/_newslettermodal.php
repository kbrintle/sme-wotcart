<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\assets\AppAsset;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use frontend\models\ZipLookupForm;

AppAsset::register($this);

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo = SettingsSeo::find()->one();
?>

<!--Newsletter Success Modals-->

<div class="modal modal__ui fade" id="newsletter-success" tabindex="-1" role="dialog" aria-labelledby="newsletterModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Welcome to Bed Heads!!</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab dignissimos excepturi qui rerum sint? Atque debitis delectus in ipsum iusto mollitia placeat quaerat quasi quo reiciendis. Harum mollitia officiis quo.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>