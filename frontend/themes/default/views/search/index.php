<?php

use common\components\CurrentStore;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\catalog\CatalogProduct;

$this->title = "Search";

?>

<div class="bg-lightgray content-pad">
    <div class="container">
        <div class="row pad-sm">
            <div class="col-md-8 col-md-offset-2">
                <?php $form = ActiveForm::begin([
                    'id' => 'global_search',
                    'action' => '/' . CurrentStore::getStore()->url . '/search',
                    'method' => 'GET'
                ]);
                ?>
                <?= $form->field($model, 'keyword')->textInput([
                    'name' => "q",
                    'placeholder' => 'Search',
                    'class' => 'form-control'
                ])->label(false); ?>
                <?= Html::submitButton('', ['class' => 'hidden']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<section class="">
    <div class="container">
        <?php if (!$model->keyword): ?>
            <div class="row pad-sm">
                <div class="col-xs-12 text-center">
                    <p>You didn't search for anything, so we don't know what to find for you.</p>
                    <p>Head back up to that search box and give it another try!</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Tab panes -->
                    <?php if (count($product_ids)): ?>
                        <div class="search_group">
                            <div class="row form-group">
                                <?php foreach ($product_ids as $product_id): ?>
                                    <?php if ($model = CatalogProduct::findOne(['id' => $product_id])):; ?>
                                        <?= $this->render('partials/_product_panel_grid', [
                                            'model' => $model
                                        ]) ?>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <h2 class="text-center pad-btm pad-top">No Products found</h2>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


