<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\catalog\CatalogCategory */

$this->title = 'Create Catalog Category';

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="container-fluid pad-xs">
        <div class="catalog-category-create">
            <div class="panel panel__ui">
                <div class="panel-body">
    
                    <?= $this->render('partials/_form', [
                        'model' => $model,
                        'form'  => $form,
                    ]) ?>
    
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
