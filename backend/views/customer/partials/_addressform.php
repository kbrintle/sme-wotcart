<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\core\Regions;
use common\models\core\Subregions;
use common\models\customer\CustomerAddress;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id'=>'address-form','address-id'=> $address->address_id]]);?>
<?= $form->field($address, 'address_id')->hiddenInput()->label(false); ?>
<?= $form->field($address, 'firstname'); ?>
<?= $form->field($address, 'lastname'); ?>
<?= $form->field($address, 'address_1'); ?>
<?= $form->field($address, 'address_2'); ?>
<?= $form->field($address, 'city'); ?>
<?= $form->field($address, 'postcode'); ?>
<?= $form->field($address, 'region_id')->dropdownlist(ArrayHelper::map(\common\models\core\CountryRegion::find()->where(["country_id"=>'US'])->all(), 'id', 'default_name')); ?>
<?//= $form->field($address, 'region_id')->dropdownlist(ArrayHelper::map(Regions::find()->where(["fips" => "US"])->all(), 'id', 'country')); ?>
<?= $form->field($address, 'phone'); ?>
<?= $form->field($address, 'fax'); ?>
<?php ActiveForm::end(); ?>