<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms_page">
    <div class="container">

        <div class="gutter-vertical">
            <?= $this->render("partials/$model->template", [
                    'model' => $model
                ]); ?>
        </div>
    </div>
</div>
