<?php

use yii\widgets\ActiveForm;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogProduct;
use noam148\imagemanager\components\ImageManagerInputWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$baseImage = CatalogAttributeValue::findOne([
    'attribute_id' => CatalogProduct::getAttributeValue($model->id, 'base-image'),
    'product_id'   => $model->id
]);
if (empty($baseImage))
    $baseImage = new CatalogAttributeValue();

$this->title = 'Product Media';
?>


<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="product-media">

            <div class="row action-row">
                <div class="col-md-12">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary pull-right']) ?>
                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['product/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                </div>
            </div>

            <div class="panel panel__ui">
                <div class="panel-heading">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="panel-body">

                    <?php echo $form->field($baseImage, 'value')->widget(ImageManagerInputWidget::className(), [
                        'showPreview' => true,
                        'showDeletePickedImageConfirm' => true
                    ])->label('Featured Image'); ?>

                </div>
            </div>

        </div>
    </div>
<?php ActiveForm::end(); ?>
