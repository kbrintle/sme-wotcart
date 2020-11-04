<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Targeted Promotion';
?>
<div class="container-fluid pad-xs">
    <div class="promotion-create">
        <div class="row">
            <div class="col-md-12">
                <div class="empty-state text-center">
                    <h3>What kind of Discount would you like to create?</h3>
                    <p>To get started, click a promotion type button below.</p>
                    <div class="row">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="col-md-4 col-md-offset-2">
                            <div class="panel panel__new-product">
                                <button name="type" type="submit" value="discount">
                                    <div class="panel-body">
                                        <div class="new-product-type">
                                            <i class="material-icons">add_circle_outline</i>
                                            <h4>Conditional Discount</h4>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="panel panel__new-product">
                                <button name="type" type="submit" value="buy_x_get_y">
                                    <div class="panel-body">
                                        <div class="new-product-type">
                                            <i class="material-icons">add_circle_outline</i>
                                            <h4>Buy X Get Y</h4>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>