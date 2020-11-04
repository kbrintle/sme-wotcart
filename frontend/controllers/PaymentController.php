<?php

namespace frontend\controllers;

use common\models\billing\Stripe;
use Yii;
use yii\web\Controller;

class PaymentController extends Controller
{

    public function actionStripe($stub){
        $token = $stub;

        if($token){
            $stripe = new Stripe($token);
            $stripe->createCharge(12345);
        }
    }

}