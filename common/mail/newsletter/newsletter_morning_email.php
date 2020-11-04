<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns=3D"http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv=3D"Content-Type" content=3D"text/html; charset=3DUTF-8" />

    <title></title>
</head>
<body>
<!--   STYLES START   -->
<style>
    @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro');
    @import url('https://fonts.googleapis.com/css?family=Montserrat');


    body{
        background-color:#F5F7F8;
        color:#77909C;
        font-family: Source Sans Pro, sans-serif;
        letter-spacing:1.5px;
    }
    nav{
        background-color: #ffffff;
        padding: 20px;
        width: 100%;
        margin-left: -15px;
        margin-top: -10px;
    }
    .img-container{
        background-image: url("<?= Yii::$app->params['base_url'].'/uploads/email/photos/masthead.jpg';?>");
        width: 100%;
        height:350px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        text-align:center;
        margin:auto;
        padding:0;
    }
    .img-container2{
        background-image: url("<?= Yii::$app->params['base_url'].'/uploads/email/photos/Shot_25_070215_145.jpg';?>");
        width: 100%;
        height: 800px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        text-align:center;
        margin:auto;
        padding:0;
    }
    .img-container3{
        background-image: url("<?= Yii::$app->params['base_url'].'/uploads/email/photos/wakeup.png';?>");
        height:125px;
        width:125px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        margin-left:-3px;
    }

    p{
        font-size: 18px;
        padding:5px;
    }
    a{
        text-decoration:none;
        color:#77909C;
        margin: 0px 8px 0px 8px;
    }
    .line{
        color:#77909C;
        text-decoration: underline;
        padding-left:85%;
    }
    h1{
        font-size: 60px;
        font-weight: 600;
        text-align: center;
        font-family: 'Montserrat-Bold', sans-serif;
        letter-spacing: 0px;
    }
    h2{
        font-size: 38px;
        font-weight: 600;
        text-align: center;
        font-family: 'Montserrat-Bold', sans-serif;
        letter-spacing: 0px;
    }
    .gutter{
        padding:15px 50px 50px 50px;
        display:inline-block;
    }
    .smaller-gutter{
        padding:25px 55px 25px 55px;
        display:inline-block;
    }
    .main-container{
        background-color:white;
        width:100%;
    }

    .center{
        text-align: center;
        margin-top:40px;
    }
    .blue{
        color:#2196F3;

    }
    .navy{
        color:#004270;
        font-weight: 600;

    }
    .white{
        color: #ffffff;
        padding-top: 124px;
        padding-bottom: 9px;
        font-size: 33px;
    }
    .blue-btn{
        color: #ffffff;
        background-color: #2196F3;
        border-radius: 50px;
        padding: 20px 60px;
    }
    .small{
        color:#77909C;
        font-size:14px;
    }
    .icon{
        width:24px;
        display:inline-block;
    }
    .padding{
        padding:30px 20px;
    }
</style>

<!--   STYLES END   -->

<!--   NAV START   -->

<nav>
    <a class="line" href="#"><small>View in Browser</small></a>
</nav>

<!--  NAV END  -->


<!--  MAIN CONTAINER START  -->

<div class="gutter">
    <div class="gutter outer-container">
        <div>
            <img src="<?= Yii::$app->params['base_url'].'/uploads/email/photos/AmMat_logo_v1.svg';?>" style="width:150px; padding-bottom: 20px;"/>
        </div>

        <div class="img-container2">
            <div style="text-align: center; background-color: #004270;" class="padding">
                <p class="blue" style="letter-spacing: 0px; margin-bottom:0;">
                    October 2016
                </p>
                <h2 style="color:white; margin-top:0;">
                    Bed Heads Newsletter
                </h2>
            </div>
        </div>
        <div class="main-container" style="margin-top:-3px;">
            <div class="smaller-gutter">
                <div style="text-align: center;">
                    <h2 class="navy">
                        Wake Up With Our Curated <br> Playlist On Spotify
                    </h2>
                    <div style="border: 2px solid #F5F7F8;">
                        <div style="width:20%; display: inline-block;">
                            <div  class="img-container3">
                                <img src="<?= Yii::$app->params['base_url'].'/uploads/email/icons/ic_play_circle_outline_black_18px.svg';?>" alt="" style="width:48px; margin-top:37px;" />

                            </div>
                        </div>
                        <div style="width:79%; display:inline-block; text-align: left; vertical-align: top;">
                            <div style="width:90%; display: inline-block;">
                                <p>
                                <span style="color:black;">
                                    Awaken
                                </span>
                                    <br>
                                    Dario Martinille
                                </p>
                                <small>
                                    <?= Yii::$app->name; ?>
                                </small>
                            </div>
                            <div style="width:9%; display: inline-block;">
                                <img src="<?= Yii::$app->params['base_url'].'/uploads/email/icons/spotify.svg';?>" alt="" style="width:24px;" />
                            </div>

                        </div>
                    </div>
                    <div style="border-left: 2px solid #F5F7F8; border-right: 2px solid #F5F7F8; border-bottom: 2px solid #F5F7F8;  text-align: left; padding:50px;">
                        <img src="<?= Yii::$app->params['base_url'].'/uploads/email/photos/wakeup.png';?>" alt="" style="max-width:50%;"/>
                    </div>
                    <div class="padding">
                        <h4 class="blue" style="font-size:18px; margin-bottom:-20px;">
                            #MORNINGBEDHEADS
                        </h4>
                        <h2 class="navy">
                            7 Awesome Coffee Shops You <br>Must Try
                        </h2>
                    </div>
                </div>
            </div>
            <img src="<?= Yii::$app->params['base_url'].'/uploads/email/photos/photo-1444418776041-9c7e33cc5a9c-mask-24.png';?>" alt="" style="width:100%;"/>


            <div class="smaller-gutter">
                <div style="text-align: center;">
                    <div style="text-align: left" class="padding">
                        <p>
                            When your bed is your favorite hello and hardest goodbye, it's not easy to leave it in the morning. Instead of jumping out of the covers, consider these seven ways to be more productive by staying in bed instead:
                        </p>
                    </div>
                    <div  style="border-top:solid 3px #F5F7F8;" class="padding">
                        <h4 class="blue" style="font-size:18px; margin-bottom:-20px;">
                            #MORNINGBEDHEADS
                        </h4>
                        <h2 class="navy">
                            10 Breakfast Tweets To Start<br> Your Morning Off Right
                        </h2>
                    </div>

                </div>
            </div>
            <img src="<?= Yii::$app->params['base_url'].'/uploads/email/photos/Screen-Shot-2016-05-02-at-4.11.59-PM-mask-26.png';?>" alt="" style="width:100%;"/>

            <div class="smaller-gutter">
                <div style="text-align: left;">
                    <p>
                        FOMO? More like Fear of Missing Out on Sleep. Here's how to bail on your plans and stay in bed this weekend.
                    </p>
                </div>
            </div>


            <div class="img-container">
                <h2 class="white">
                    Wake up on the <br>right side.
                </h2>
                <a href="" class="blue-btn">
                    Find your Sleep Simple
                </a>
            </div>
        </div>

        <div class="center">
            <p>

                <a href="">
                    <img src="<?= Yii::$app->params['base_url'].'/uploads/email/icons/social_icons/fb_icon.svg';?>"  class="icon" />
                </a>
                <a href="">
                    <img src="<?= Yii::$app->params['base_url'].'/uploads/email/icons/social_icons/ig_icon.png';?>"  class="icon" />
                </a>

            </p>
            <p class="small">
                &copy; 2016 <?= Yii::$app->name; ?>
            </p>
            <p class="small">
                <a href="#">Unsubscribe</a>
                |
                <a href="#">Edit Preferences</a>
                |
                <a href="#">Privacy Policy</a>
            </p>
        </div>
    </div>
</div>
<!--  MAIN CONTAINER END  -->


</body>
</html>