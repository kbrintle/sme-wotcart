<?php

use app\components\StoreUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\store\StoreEvent;

$this->title = 'SME Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-site">
    <section>
        <div class="category-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-header-img" style="background-image: url('/themes/default/_assets/src/images/sme/SME-Events_V1.jpg'); -webkit-background-size: cover;
                            -moz-background-size: cover;
                            -o-background-size: cover;
                            background-size: cover;
                            background-repeat: no-repeat;
                            background-position: 50% 50%; ">

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row pad-xs">
            <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
                <ul class="events">
                    <?php foreach ($events as $event): ?>
                        <li class="events-event">
                            <div class="events-event--icon">
                                <i class="material-icons">
                                    event
                                </i>
                            </div>
                            <div class="events-event--content">
                                <h3 class="title"><?= $event->title ?></h3>
                                <div class="events-event--meta">
                                    <span class="date"><?= StoreEvent::getEventDateHtml($event->id)?></span>
                                    <a href="<?= StoreUrl::to('events/' . $event->slug) ?>" class="btn btn-link text-left">View
                                        event</a>
                                </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>


