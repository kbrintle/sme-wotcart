<div class="checkout-order-delivery">
    <div class="panel panel__ui">
        <div class="panel-heading panel__ui-heading">
            <h3 class="panel__ui-heading-ttl">Delivery Method</h3>
        </div>
        <div class="panel-body panel__ui-body">
            <?= $form->field($model, 'delivery_method')->radioList([
                        'store_delivery'    => 'Store Delivery',
                        'store_pickup'      => 'Store Pickup'
                    ],
                    [
                        'item'  => function($index, $label, $name, $checked, $value){
                            $price      = 'FREE';
                            $ng_price   = 0;
                            $class      = 'store-pickup';
                            if($value  == 'store_delivery'){
                                $price      = Yii::$app->cart->getDisplayShipping();
                                $ng_price   = Yii::$app->cart->getDisplayShipping();
                                $class      = 'store-delivery';
                            }

                            $output = "<div class='radio $class'>
                                            <label
                                                ng-click='setShipping($ng_price)'>
                                                <input type='radio' name='$name' value='$value'>
                                                $label
                                            </label>
                                            <span class='pull-right'>$price</span>
                                        </div>";
                            return $output;
                        }
                    ])->label(false); ?>
        </div>
    </div>
</div>