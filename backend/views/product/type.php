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

                    <?= Html::a( '<i class="glyphicon glyphicon-arrow-left"></i>', Url::to(['product/index']), ['title' => 'Back', 'class' => 'btn btn-secondary pull-left']); ?>

                <div class="empty-state text-center">
                    <h3>What kind of product would you like to create?</h3>
                    <p>To get started, click a product type button below.</p>
                    <div class="row">
                        <?php $form = ActiveForm::begin(); ?>
                            <?php foreach ($productTypes as $key => $productType): ?>
                                <div class="col-md-4">
                                    <div class="panel panel__new-product">
                                        <button href="#" name="type" type="submit" value="<?php echo $key; ?>">
                                            <div class="panel-body">
                                                <div class="new-product-type">
                                                    <i class="material-icons">add_circle_outline</i>
                                                    <h4><?php echo $productType; ?></h4>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>