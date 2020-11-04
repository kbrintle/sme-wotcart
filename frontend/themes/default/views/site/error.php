<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use app\components\StoreUrl;

$this->title = $name;
?>
<div class="container-fluid">
    <div class="site-error content-pad">
        <div class="row pad-sm">
            <div class="col-md-8 col-md-offset-2 text-center">
                <h1><?= Html::encode($this->title) ?></h1>
                <h3><?= nl2br(Html::encode($message)) ?></h3>
                <p>
                    The above error occurred while the Web server was processing your request. Please <a href="mailto:info@wideopentech.com?Subject=Server%20Error">contact us</a> if you think this is a server error. Thank you.
                </p>
               
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <?php echo Html::a("Return Home", StoreUrl::homeurl(), ['class' => 'btn btn-primary btn-xl center-block']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
