

<div class="masthead" style="background-image: url('<?=$model->image?>');">
    <div class="carousel-caption">
        <div class="carousel-caption-inner">
            <h4><?=$model->title?></h4>
            <h1><?=$model->sub_title?></h1>
            <p><?=$model->content?></p>
            <a class="btn btn-primary btn-xl" href="<?=$model->button_url?>"><?=$model->button_text?></a></div>
    </div>
</div>

<ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
</ol>