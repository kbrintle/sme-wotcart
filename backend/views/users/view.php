<?php

use yii\helpers\Html;
use common\models\core\Admin;

/* @var $this yii\web\View */
/* @var $model app\models\Admin */


$user = Admin::findIdentity($model->id);
?>


<div class="container-fluid user-view">
    <div class="col-xs-6 text-right pull-right">

        <?= Html::a('Edit', ['update', 'id' => $user->id], ['class' => 'btn btn-add']) ?>
    </div>
    <div class="content-pad">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-am">
                    <div class="panel-body">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="profile-head user-profile-head">
                                <div class="profile-head-img text-center">
                                    <?php if ($user->avatar): ?>
                                        <img src="<?= Yii::$app->homeUrl . 'uploads/' . $user->avatar ?>"
                                             class="img-circle ammatUser ammatUser-lg"/>
                                    <?php else: ?>
                                        <div style="margin: 0px auto; padding: 20px 0px; font-size: 25px; color: #FFF;"
                                             class="img-circle ammatUser-lg ammatUser">
                                            <?= Admin::findIdentity($user->id)->getInitials() ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="user-profile-meta">
                                    <span class="profile-head-name"><?= "$user->first_name $user->last_name" ?></span>

                                    <span class="profile-head-email">
                                        <a href="mailto:<?= $user->email ?>">
                                            <i class="fa fa-envelope" aria-hidden="true"></i><?= $user->email ?></span>
                                    </a>
                                </div>
                                <div class="user-company-info">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>