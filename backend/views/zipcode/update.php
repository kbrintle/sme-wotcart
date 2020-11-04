<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StoreZipCode */

$this->title = 'Update Store Zip Code: ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Store Zip Codes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<section class="content">
    <div class="store-zip-code-update">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</section>
