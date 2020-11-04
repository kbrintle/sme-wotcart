<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\store\NewsletterSubscriber */

$this->title = 'Create Newsletter Subscriber';

?>
<div class="newsletter-subscriber-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>