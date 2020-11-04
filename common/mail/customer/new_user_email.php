<div class="smaller-gutter" style="width: 100%;display: inline-block;">
    <div>
        <h1 style="color:#999; font-size:28px; font-weight: 600; letter-spacing: 4px; text-align: left;">
            Your Account is ready to go.
        </h1>
        <p style="color:#999; font-size: 16px;padding: 5px;">
            Thanks for creating an account on <?= Yii::$app->name; ?><span class="period">.</span>
            <br>
            Your username is: <?php echo $user->email ?>
        </p>
        <p style="color:#999; font-size: 16px;padding: 5px;">
            Thanks,
            <br>
            <span class="italic">The <?= Yii::$app->name; ?> Team</span>
        </p>
    </div>
</div>
