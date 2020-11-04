<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'New Product';
?>
<div class="container-fluid pad-xs">
    <div class="catalog-brand-index">
        <div class="row">
            <div class="col-md-12">
                <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['product/new']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>
                <div class="empty-state text-center">
                    <h3>Set Product Attributes</h3>
                    <div class="row">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="col-md-6 col-md-offset-3">
                            <div class="panel panel__new-product-ui">
                                <div class="panel-body">
                                    <select id="attribute-set" class="form-control" name="AttributeSet[set_id]">
                                        <option value="">Select one</option>
                                        <?php foreach ($attributeSets as $key => $attributeSet): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $attributeSet; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <br/>
                                    <div class="text-center">
                                        <?php echo Html::submitButton('Use Set', ['class' => 'btn btn-primary']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
