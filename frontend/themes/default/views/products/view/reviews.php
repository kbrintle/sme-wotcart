<?php
use yii\helpers\Html;
use app\components\StoreUrl;
?>

<section id="reviews" class="reviews">
    <div class="container">
        <div class="row">
            <div class="col-md-12 clearfix">
                <div class="reviews-action">
                    <h3 class="pull-left">Ratings & Reviews</h3>
                    <div class="pull-right">
                        <a href="#" data-toggle="modal" data-target="#write-review" class="btn btn-primary btn-responsive btn-no-mrg">Write a review</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="customer-reviews">

                <?php $product_reviews = $product->reviews;
                    if( count($product_reviews) > 0 ): ?>
                    <?php foreach($product_reviews as $product_review): ?>
                        <div class="col-md-12">
                            <div class="review-single">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4><?= $product_review['first_name']; ?> <?= $product_review['last_name']; ?></h4>
                                        <h3><?= $product_review['title']; ?></h3>
                                        <p><?= $product_review['detail']; ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="review-rating pull-right">
                                            <div class="rating">
                                                <div class="rating-stars">
                                                    <ul>
                                                        <li class="rating-stars-single <?= $product_review['rating'] >= 1 ? 'filled' : ''; ?>">
                                                            <i class="material-icons"><?= $product_review['rating'] >= 1 ? 'star' : 'star_border'; ?></i>
                                                        </li>
                                                        <li class="rating-stars-single <?= $product_review['rating'] >= 2 ? 'filled' : ''; ?>">
                                                            <i class="material-icons"><?= $product_review['rating'] >= 2 ? 'star' : 'star_border'; ?></i>
                                                        </li>
                                                        <li class="rating-stars-single <?= $product_review['rating'] >= 3 ? 'filled' : ''; ?>">
                                                            <i class="material-icons"><?= $product_review['rating'] >= 3 ? 'star' : 'star_border'; ?></i>
                                                        </li>
                                                        <li class="rating-stars-single <?= $product_review['rating'] >= 4 ? 'filled' : ''; ?>">
                                                            <i class="material-icons"><?= $product_review['rating'] >= 4 ? 'star' : 'star_border'; ?></i>
                                                        </li>
                                                        <li class="rating-stars-single <?= $product_review['rating'] >= 5 ? 'filled' : ''; ?>">
                                                            <i class="material-icons"><?= $product_review['rating'] >= 5 ? 'star' : 'star_border'; ?></i>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <span class="review-date pull-right"><?= date('F Y', $product_review['created_at']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                        <div class="col-md-12">
                            <div class="review-single reviews-empty text-center">
                                <p>This product currently does not have any reviews. <a href="#" data-toggle="modal" data-target="#write-review">Could you be the first to write one</a>?</p>
                            </div>
                        </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<!-- Render Review Modal -->
<?= $this->render('_review_modal', [
    'product' => $product
]); ?>