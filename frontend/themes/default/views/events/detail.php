<?php

use app\components\StoreUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\store\StoreEvent;


$this->title = $event->title;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => [StoreUrl::to("events")]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="events-site--details">
    <section>
        <div class="category-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="container">
                        <?php if ($event->featured_image_path): ?>
                            <div class="category-header-img" style="background-image: url(); -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: 50% 50%; ">
                                <img class="bound" src="/<?= $event->featured_image_path; ?>"/>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row pad-xs">
            <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                <div class="event">
                    <h2 class="title"><?= $event->title ?></h2>
                    <div class="events-event--meta">
                        <span class="date"><?= StoreEvent::getEventDateHtml($event->id)?></span>
                    </div>
                    <p class="pad-btm-sm">
                        <?= $event->content ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

