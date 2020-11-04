<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\StoreZipCode */

$this->title = 'Create Store Zip Code';
//$this->params['breadcrumbs'][] = ['label' => 'Store Zip Codes', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content pad-xs">
    <div class="store-zip-code-create">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</section>