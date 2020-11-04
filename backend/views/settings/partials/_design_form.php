<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\helpers\FormHelper;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\settings\SettingsStore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="settings-store-form">
    <?php $form = ActiveForm::begin(['id' => 'SettingsStore']); ?>
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary pull-right']) ?>
    <?php if (\backend\components\CurrentUser::isAdmin()): ?>
        <div class="pad-top">
            <h4>Logo</h4>
            <div>
                <img src="<?php echo \frontend\components\Assets::mediaResource(\common\models\core\CoreConfig::getStoreConfig('general/design/logo'), \common\components\CurrentStore::getStoreId()) ?>"
                     width="100" class="pull-right"/>
                <?= $form->field($model, 'logo')->fileInput() ?>
            </div>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>
</div>