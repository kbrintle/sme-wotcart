<?php
use app\components\StoreUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="blg-single content-pad">
    <div class="blg-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <span class="label label-blg"><?= $post->category ? $post->category->title : ''; ?></span>
                    <h1 class="blg-post-title"><?php echo $post->title ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="blg-post-img">
        <?php if( $post->featured_image_path ): ?>
            <div class="blg-post-img-banner" style="background-image:url('/<?= $post->featured_image_path; ?>');"></div>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="row blg-header">
            <div class="col-md-4">
                <div class="blg-single-meta">
                    <span class="blg-post-author">By <?php echo $post->user ?></span>
                    <span class="blg-post-meta"><?php echo date('M d, Y', strtotime($post->created_time))  ?></span>
                </div>
                <div class="tags">
                    <?php $tags = $post->tagList;
                    if( count($tags) > 0 ): ?>
                        <h4>Tagged</h4>
                        <?php foreach($tags as $tag): ?>
                            <span class="label label-blg"><?= $tag; ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="social-share">
                    <?php $social_url = $post->socialUrl; ?>
                    <!-- Link share buttons to share to facebook and twitter -->
                    <a href="https://www.facebook.com/sharer?u=<?= $social_url; ?>&quote=<?= rawurlencode($post->title); ?>" target="_blank" class="btn btn-fb">Share on Facebook</a>

                    <a href="https://twitter.com/share?url=<?= $social_url; ?>&amp;text=<?= rawurlencode($post->title); ?>&amp;hashtags=sleepsimple" target="_blank" class="btn btn-tw">Share on Twitter</a>


                    <div class="blg-newsletter social-share">

                        <div id="newsletter_messages" class="messages">
                            <?php if( $newsletter->successMessage ): ?>
                                <div class="alert alert-success">
                                    <?= $newsletter->successMessage; ?>
                                </div>
                            <?php endif; ?>
                            <?php if( $newsletter->errorMessage ): ?>
                                <div class="alert alert-danger">
                                    <?= $newsletter->errorMessage; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php $form = ActiveForm::begin([
                                'id' => 'newsletter_form',
                            ]); ?>
                            <?= $form->field($newsletter, 'email')->textInput([
                                    'class'         => 'form-control',
                                    'placeholder'   => 'Enter your Email'
                                ])->label(false); ?>
                            <?= Html::submitButton('Subscribe', ['class' => 'hidden']) ?>
                        <?php ActiveForm::end(); ?>

                        <a id="newsletter_subscribe_button" class="btn btn-primary" onclick="clickSubscribe()">Subscribe to Newsletter</a>

                        <script type="text/javascript">
                            function clickSubscribe(){
                                var subscribe_button = document.getElementById('newsletter_subscribe_button');
                                var form = document.getElementById('newsletter_form');

                                if( form.classList.contains('active') ){
                                    submitForm();
                                }else{
                                    subscribe_button.classList.add('active');
                                    subscribe_button.classList.add('fa');
                                    subscribe_button.classList.add('fa-envelope');
                                    subscribe_button.innerText = null;
                                    form.classList.add('active');
                                }
                            }

                            function submitForm(){
                                var form = document.getElementById('newsletter_form');
                                form.submit();
                            }
                        </script>
                    </div>

                </div>
            </div>
            <div class="col-md-8">
                <div class="post-content pad-btm">
                   <p><?php echo $post->post_content ?></p>
                </div>
            </div>
        </div>
    </div>
</div>