
<div class="img-container">
    <img style="width:100%;" src="photos/masthead.jpg">
</div>
<div class="smaller-gutter" style="padding: 25px 30px 25px 30px;display: inline-block;">
    <div>
        <h1 style="color:#999; font-size:28px; font-weight: 600; letter-spacing: 4px; text-align: left;">
            New Store Submission
        </h1>

        <p>
            Name: <?php echo $model->name; ?>
        </p>
        <p>
            Email: <?php echo $model->email; ?>
        </p>

        <p>
            <?php echo $model->body; ?>
        </p>
    </div>
</div>

