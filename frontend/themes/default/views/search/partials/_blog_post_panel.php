<?php
use app\components\StoreUrl;
use yii\helpers\Html;
use frontend\components\Assets;
?>
<div class="col-md-4 search_panel search_panel-blog_post grid-item">
    <div class="panel">
        <div class="blg-post">
            <div class="blg-post-img">
                <?php if( $model->featured_image_path ): ?>
                    <img src='/<?= $model->featured_image_path; ?>' />
                <?php endif; ?>
            </div>
            <div class="blg-post-content">
                <span class="label label-blg"><?= $model->category ? $model->category->title : ''; ?></span>
                <?php echo Html::a("<h2 class='blg-post-title'>$model->title</h2>", StoreUrl::to('blog/'. $model->identifier)); ?>
                <span class="blg-post-meta"><?= date('M dS, Y', strtotime($model->created_time)); ?></span>
            </div>
        </div>
    </div>
</div>
