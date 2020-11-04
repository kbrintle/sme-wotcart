<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\components\CurrentStore;
use common\models\core\Store;
use common\components\helpers\FormHelper;

/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogAttributeSet */
/* @var $form yii\widgets\ActiveForm */

?>

<?php echo $form->field($model, 'label')->textInput(['maxlength' => true]); ?>
<?php echo $form->field($model, 'is_active')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>
<?php if (CurrentStore::isNone()): ?>
    <?php echo $form->field($model, 'is_default')->dropdownList(FormHelper::getBooleanValues(), ['prompt'=>'Select one']); ?>
<?php endif; ?>





