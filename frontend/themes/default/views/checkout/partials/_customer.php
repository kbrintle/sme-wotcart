<div class="checkout-order-customer"
    ng-init="customer='<?= $model->customer; ?>'">
    <div class="panel panel__ui">
        <div class="panel-heading panel__ui-heading">
            <h3 class="panel__ui-heading-ttl">Customer</h3>
        </div>
        <div class="panel-body panel__ui-body">
            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>
                                <?= $form->field($model, 'customer')->radio([
                                    'value'     => 1,
                                    'ng-model' => 'customer'
                                ], false)->label(false); ?>
                                <p class="inline margin-left no-space">Checkout as Guest</p>
                            </label>
                        </div>

                        <div class="col-md-12"
                             ng-if="customer==1">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?= $form->field($model, 'customer_guest_email')->textInput([
                                        'class' => 'form-control'
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-md-12">
                            <label>
                                <?= $form->field($model, 'customer')->radio([
                                        'value'     => 2,
                                        'ng-model' => 'customer'
                                    ], false)->label(false); ?>
                                <p class="inline margin-left no-space">Returning Customer</p>
                            </label>
                        </div>

                        <div class="col-md-12"
                            ng-if="customer==2">
                            <?php if(Yii::$app->user->isGuest): ?>
                                <div class="col-xs-12 col-md-6">
                                    <?= $form->field($model, 'customer_login_email')->textInput([
                                            'class' => 'form-control'
                                        ]); ?>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <?= $form->field($model, 'customer_login_password')->textInput([
                                            'class' => 'form-control'
                                        ]); ?>
                                </div>
                            <?php else: ?>
                                <p>Logged in as: <?= Yii::$app->user->identity->email; ?></p>
                                <?= $form->field($model, 'customer_id')->hiddenInput()->label(false); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



