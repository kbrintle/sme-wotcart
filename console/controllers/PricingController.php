<?php

namespace console\controllers;

use common\models\billing\TaxJar;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;


class PricingController extends Controller{

    public function actionTax($zip_code, $price){
        $sales_tax = TaxJar::calculateTax($zip_code, $price);
        echo $this->ansiFormat("Sales Tax for $$price sale at $zip_code: $$sales_tax \n", Console::FG_GREEN);
    }

}