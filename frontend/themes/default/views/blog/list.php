<?php

use yii\helpers\Html;
use app\components\StoreUrl;
use frontend\components\Assets;
use yii\widgets\ActiveForm;

?>

<div class="blg content-pad">
    <div class="blg-header">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <h1 class="blg-title"><?= Yii::$app->name; ?></h1>
                </div>
                <div class="col-md-3">
                    <?php $form = ActiveForm::begin([
                        'id'        => 'blog_search'
                    ]);
                    ?>
                    <div class="input-group">
                        <?= $form->field($model, 'keyword')->textInput([
                            'placeholder'   => 'Search by Keyword',
                            'class'         => 'form-control'
                        ])->label(false); ?>
                        <span class="input-group-btn">
                            <?= Html::submitButton('<i class="material-icons">search</i>', ['class'=>'btn btn-icon search-icon']) ?>
                        </span>
                    </div><!-- /input-group -->
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-2">
                    <?php echo Html::a("Subscribe", 'http://eepurl.com/cO0fjv', ['class' => 'btn btn-primary btn-responsive']); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="blg-grid pad-xs">
        <div class="container">

            <?php if( $model->keyword ): ?>
                <div class="row">
                    <div class="col-xs-12">
                        <h4>Search Results for: <?= $model->keyword; ?></h4>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php foreach($model->posts as $index => $post): ?>
                    <?php if ($index == 0): ?>
                        <div class="col-md-12">
                            <div class="blg-post featured">
                                <div class="row">
                                    <div class="col-md-8 col-md-push-4">
                                        <div class="blg-post-img">
                                            <?php if( $post->featured_image_path ): ?>
                                                <img src='/<?= $post->featured_image_path; ?>' />
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-md-pull-8">
                                        <div class="blg-post-content">
                                            <span class="label label-blg"><?= $post->category ? $post->category->title : ''; ?></span>
                                            <?php echo Html::a("<h2 class='blg-post-title'>$post->title</h2>", StoreUrl::to('learn/'. $post->identifier)); ?>
                                            <span class="blg-post-author">By <?php echo $post->user ?></span>
                                            <span class="blg-post-meta"><?php echo date('M d, Y', strtotime($post->created_time))  ?></span>
                                            <p class="blg-post-description"><?php echo $post->excerpt; ?></p>
                                        </div>
                                        <div class="blg-post-action">
                                            <a href="<?php echo StoreUrl::to('learn/'. $post->identifier) ?>" class="btn btn-read-more">Read More <i class="material-icons">chevron_right</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-4">
                            <div class="blg-post">
                                <div class="blg-post-img">
                                    <?php if( $post->featured_image_path ): ?>
                                        <img src='/<?= $post->featured_image_path; ?>' />
                                    <?php endif; ?>
                                </div>
                                <div class="blg-post-content">
                                    <span class="label label-blg"><?= $post->category ? $post->category->title : ''; ?></span>
                                    <?php echo Html::a("<h2 class='blg-post-title'>$post->title</h2>", StoreUrl::to('learn/'. $post->identifier)); ?>
                                    <span class="blg-post-meta"><?php echo date('M d, Y', strtotime($post->created_time))  ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
