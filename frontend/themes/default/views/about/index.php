<?php
use common\components\CurrentStore;
?>
<div class="about">
    <div class="container">
        <div class="row pad-sm">
            <div class="col-md-10 col-md-offset-1 text-center">
                <h2><?php echo CurrentStore::getStore()->name ?></h2>
                <p><?php echo isset($model->about_text) ? $model->about_text : ''; ?></p>
            </div>
        </div>
    </div>
</div>


