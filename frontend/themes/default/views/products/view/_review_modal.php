<?php
use app\components\StoreUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<div class="modal modal__ui fade" id="write-review" tabindex="-1" role="dialog" aria-labelledby="reviewModal">
    <div class="modal-dialog" role="document">
<?php if(Yii::$app->user->isGuest): ?>
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Log in to leave a review</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <?php $model = new \frontend\models\LoginForm();

                    $form = ActiveForm::begin([
                        'id'    => 'login-form',
                        'action'=> StoreUrl::to('account/login').'?redir='.$_SERVER['REQUEST_URI']
                    ]); ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <div style="color:#999;margin:1em 0">
                        If you forgot your password you can <?php echo Html::a('Reset it', StoreUrl::to('account/request-password-reset')); ?>.
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg btn-responsive', 'name' => 'login-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="modal-content"
        ng-controller="CreateReviewController">
        <div class="modal-header"
             ng-if="!success">
            <h4 class="modal-title" id="myModalLabel">How would you rate this product?</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="success-message"
                        ng-if="success">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div class="success-badge">
                                    <i class="material-icons">check</i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <h4>Your Review was successfully submitted for review!</h4>
                            </div>
                        </div>
                    </div>

                    <form ng-submit="submit()"
                        ng-if="!success">

                        <div class="form-group rating">
                            <label>Rating</label>
                            <div class="rating-stars">
                                <ul ng-init="form.rating=0">
                                    <li class="rating-stars-single"
                                        ng-click="form.rating=1"
                                        ng-class="{'filled':form.rating>=1}">
                                        <i class="material-icons"
                                           ng-if="form.rating>=1">star</i>
                                        <i class="material-icons"
                                           ng-if="form.rating<1">star_border</i>
                                    </li>
                                    <li class="rating-stars-single"
                                        ng-click="form.rating=2"
                                        ng-class="{'filled':form.rating>=2}">
                                        <i class="material-icons"
                                           ng-if="form.rating>=2">star</i>
                                        <i class="material-icons"
                                           ng-if="form.rating<2">star_border</i>
                                    </li>
                                    <li class="rating-stars-single"
                                        ng-click="form.rating=3"
                                        ng-class="{'filled':form.rating>=3}">
                                        <i class="material-icons"
                                           ng-if="form.rating>=3">star</i>
                                        <i class="material-icons"
                                           ng-if="form.rating<3">star_border</i>
                                    </li>
                                    <li class="rating-stars-single"
                                        ng-click="form.rating=4"
                                        ng-class="{'filled':form.rating>=4}">
                                        <i class="material-icons"
                                           ng-if="form.rating>=4">star</i>
                                        <i class="material-icons"
                                           ng-if="form.rating<4">star_border</i>
                                    </li>
                                    <li class="rating-stars-single"
                                        ng-click="form.rating=5"
                                        ng-class="{'filled':form.rating>=5}">
                                        <i class="material-icons"
                                           ng-if="form.rating>=5">star</i>
                                        <i class="material-icons"
                                           ng-if="form.rating<5">star_border</i>
                                    </li>
                                </ul>
                            </div>
                            <input type="hidden"
                                   ng-model="form.rating" />

                            <div class="color-danger"
                                 ng-if="errors.rating">{{errors.rating}}</div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Title"
                                ng-model="form.title"/>

                            <div class="color-danger"
                                ng-if="errors.title">{{errors.title}}</div>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="3" placeholder="Review"
                                ng-model="form.detail"></textarea>

                            <div class="color-danger"
                                 ng-if="errors.detail">{{errors.detail}}</div>
                        </div>

                        <input type="hidden" ng-init="form.product_id=<?= $product->id; ?>"
                               ng-model="form.product_id" />

                        <input type="submit" class="btn btn-primary btn-responsive" value="Submit Review"
                               ng-disabled="disabled" />
                    </form>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
    </div>
</div>
