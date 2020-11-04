<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use common\models\core\Store;


AppAsset::register($this);

//Get store specific settings
$settingsStore = SettingsStore::find()->one();
$settingsSeo = SettingsSeo::find()->one();
?>

<!--Brands Modals-->

<div class="modal modal__ui fade" id="brands" tabindex="-1" role="dialog" aria-labelledby="brandsModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="brandsModalLabel">brands modal</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="brand-img">
<!--                                <?php //echo Html::img($brand->logo); ?>-->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                        </div>
                    </div>
                </div>
            
        </div>
    </div>
</div>