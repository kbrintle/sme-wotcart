<?php
$total_reviews  = count($product->reviews);
$average_rating = $product->averageReviewRatings;
?>

<div class="rating clearfix">
    <div class="rating-stars pull-left">
        <ul>
            <li class="rating-stars-single <?= $average_rating >= 1 ? 'filled' : ''; ?>">
                <i class="material-icons"><?= $average_rating >= 1 ? 'star' : 'star_border'; ?></i>
            </li>
            <li class="rating-stars-single <?= $average_rating >= 2 ? 'filled' : ''; ?>">
                <i class="material-icons"><?= $average_rating >= 2 ? 'star' : 'star_border'; ?></i>
            </li>
            <li class="rating-stars-single <?= $average_rating >= 3 ? 'filled' : ''; ?>">
                <i class="material-icons"><?= $average_rating >= 3 ? 'star' : 'star_border'; ?></i>
            </li>
            <li class="rating-stars-single <?= $average_rating >= 4 ? 'filled' : ''; ?>">
                <i class="material-icons"><?= $average_rating >= 4 ? 'star' : 'star_border'; ?></i>
            </li>
            <li class="rating-stars-single <?= $average_rating >= 5 ? 'filled' : ''; ?>">
                <i class="material-icons"><?= $average_rating >= 5 ? 'star' : 'star_border'; ?></i>
            </li>
        </ul>
    </div>
    <div class="product-reviews pull-left">
        <a href="#reviews">
            <span class="product-reviews-count"><?= $total_reviews; ?></span><span>Reviews</span>
        </a>
    </div>
</div>