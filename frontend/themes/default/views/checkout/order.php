<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\StoreUrl;
use common\components\CurrentStore;
use frontend\components\Assets;

?>

<section class="bg-lightGray">
    <div class="container-fluid">
        <div class="row pad-sm">
            <div class="container">
                <div class="cart">
                    <div class="row">
<!--           LEFT START             -->
                        <div class="col-md-7">

<!--          CUSTOMER START                  -->
                            <div class="panel outline-gray">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12 bottom-buffer">
                                            <div class="col-md-12">
                                                <h3 class="order-id capitalize color-darkGray inline">
                                                    Customer
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
<!--       CUSTOMER END       -->
<!--   SHIPPING ADDRESS   START-->

                            <div class="panel outline-gray">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12 bottom-buffer">
                                            <div class="col-md-12">
                                                <h3 class="order-id capitalize color-darkGray inline">
                                                    shipping address
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 container-buffer">
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>First Name</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>Last Name</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Street Address</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Apartment/Suite #</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>City</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>State</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>Zipcode</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Phone</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-12 top-margin">
                                                <i class="material-icons align-middle color-darkGray">check_box_outline_blank</i>
                                                <p class="no-space inline margin-left">
                                                    Billing address is same as shipping
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
<!--       SHIPPING ADDRESS  END       -->

<!--   BILLING ADDRESS   START-->

                            <div class="panel outline-gray display-none">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12 bottom-buffer">
                                            <div class="col-md-12">
                                                <h3 class="order-id capitalize color-darkGray inline">
                                                    billing address
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 container-buffer">
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>First Name</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>Last Name</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Street Address</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Apartment/Suite #</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>City</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>State</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>Zipcode</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Phone</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
<!--       BILLING ADDRESS  END       -->

<!--   DELIVERY METHOD   START-->

                            <div class="panel outline-gray">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12 bottom-buffer">
                                            <div class="col-md-12">
                                                <h3 class="order-id capitalize color-darkGray inline">
                                                    delivery method
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 top-margin">
                                            <input type="radio" name="" value="">
                                            <p class="inline margin-left no-space">
                                                Store Delivery
                                            </p>
                                        </div>
                                        <div class="col-md-12 top-margin">
                                            <input type="radio" name="" value="">
                                            <p class="inline margin-left no-space">
                                                Store Pickup
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

<!--       DELIVERY METHOD END       -->
<!--   PAYMENT INFO    START-->
                            <div class="panel outline-gray">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12 bottom-buffer">
                                            <div class="col-md-12 row">
                                                <div class="col-md-8">
                                                    <h3 class="order-id capitalize color-darkGray inline">
                                                        payment info
                                                    </h3>
                                                </div>
                                                <div class="col-md-4 text-right header-margin">
                                                    <small class="color-gray">
                                                        <i class="material-icons align-middle">lock_outline</i>
                                                        Secure and encrypted
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 container-buffer">
                                            <div class="col-md-12">
                                                <p class="no-space">
                                                    Credit/Debit Card
                                                </p>
                                                <p>
                                                    <small class="color-darkGray no-space">
                                                        We accept all major credit cards
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Name on Card</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-12 top-margin">
                                                <label>
                                                    <small>Card Number</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>

                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>MM/YY</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>
                                            <div class="col-md-6 top-margin">
                                                <label>
                                                    <small>CVC</small>
                                                </label>
                                                <input type="text" name="" value="" class="form-control">
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
<!--   PAYMENT INFO    START-->
                        </div>

<!--     LEFT    END     -->

<!--       RIGHT    START                 -->

                        <div class="col-md-5">
                            <div class="panel outline-gray">
                                <div class="panel-body">
                                    <div class="row wide bottom-line">
                                        <div class="col-md-12">
                                            <div class="col-md-10  bottom-buffer">
                                                <h3 class="capitalize color-darkGray ">
                                                    Order Summary
                                                </h3>
                                            </div>
                                            <div class="col-md-2 text-right header-margin">
                                                <a href="">
                                                    <small class="color-primaryBlue">
                                                        Edit
                                                    </small>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row bottom-line container-buffer top-margin">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?php echo Html::img(Assets::themeResource('products/mattresses/ICO_SavantIII_PS_Silo_MPIII_500x326.png'), ['alt'=>'Sertapedic', 'class'=>'product-image img-responsive img-responsive-mobile']);?>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="color-darkerGray no-space">Private Luxury Euro Top</p>
                                                    <small class="inline color-darkGray">Queen | QTY X 1</small>
                                                </div>
                                                <div class="col-md-3 right-text">
                                                    <p class="no-space">$299.95</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 ">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?php echo Html::img(Assets::themeResource('products/mattresses/ICO_SavantIII_PS_Silo_MPIII_500x326.png'), ['alt'=>'Sertapedic', 'class'=>'product-image img-responsive img-responsive-mobile']);?>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="color-darkerGray no-space">Standard Foundation</p>
                                                    <small class="inline color-darkGray">Queen | QTY X 1</small>
                                                </div>
                                                <div class="col-md-3 right-text">
                                                    <p class="no-space">$100.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row container-buffer">
                                        <div class="cart-summary col-md-12">
                                            <div class="row">
                                                <div class="bottom-line col-md-12">
                                                    <div class="col-md-6">
                                                        <p class="header-margin float-left no-space">Subtotal:</p>
                                                    </div>
                                                    <div class="col-md-6 float-right">
                                                        <p class="header-margin float-right no-space">$399.95</p>
                                                    </div>
                                                </div>
                                                <div class="bottom-line col-md-12">
                                                    <div class="col-md-6">
                                                        <p class="header-margin float-left no-space">Shipping:</p>
                                                    </div>
                                                    <div class="col-md-6 float-right">
                                                        <p class="header-margin float-right no-space">$0.00</p>
                                                    </div>
                                                </div>
                                                <div class="bottom-line col-md-12">
                                                    <div class="col-md-6">
                                                        <p class="header-margin float-left no-space">Sales Tax:</p>
                                                    </div>
                                                    <div class="col-md-6 float-right">
                                                        <p class="header-margin float-right no-space">$16.50</p>
                                                    </div>
                                                </div>
                                                <div class="bottom-line col-md-12">
                                                    <div class="col-md-12">
                                                        <a href="#" class="code">
                                                            <h3 class="capitalize color-primaryBlue float-right">
                                                                Use Promo Code
                                                            </h3>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-6">
                                                        <p class="header-margin float-left">Total:</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <span class="float-right">
                                                        <small class="inline color-gray right-small-margin">USD</small>
                                                        <h3 class="color-green inline capitalize ">
                                                            $416.45
                                                        </h3>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <p class="color-darkGray text-center no-space margin-bottom">
                                        By clicking "Place Order" you agree to SME
                                        <a href="">
                                            Terms and Conditions
                                        </a>
                                        and
                                        <a href="">
                                            Privacy Policy
                                        </a>
                                        .
                                    </p>
                                </div>
                               <div class="col-md-12">
                                   <a href="" class="btn btn-primary btn-responsive btn-lg">
                                       PLACE ORDER
                                   </a>
                               </div>
                            </div>
                        </div>

<!--        RIGHT END                -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>