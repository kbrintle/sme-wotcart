<?php

namespace common\models\billing;

use Yii;
use yii\helpers\Json;


class TaxJar{
    public $zip_code;
    public $price;


    public function getTaxRate(){

    }

    public static function calculateTax($destination, $price, $shipping=0){
//        $client = new \Avalara\AvaTaxClient('phpTestApp', '1.0', 'localhost', 'production');
//        $client->withSecurity(Yii::$app->params['avalara']['username'], Yii::$app->params['avalara']['password']);
//
//
//        $tb = new \Avalara\TransactionBuilder($client, "CMB369", \Avalara\DocumentType::C_SALESINVOICE, 'ABC');
//
//
//        $t = $tb->withAddress('SingleLocation',
//            $destination ? $destination->address : '',
//            null,
//            null,
//            $destination ? $destination->city : '',
//            $destination ? $destination->state : '',
//            $destination ? $destination->zipcode : '',
//            'US')
//            ->withLine($price, 1, "P0000000")
//            ->create();
//
//        return $t->totalTaxCalculated;
//        return;



        $client = \TaxJar\Client::withApiKey("3cb3886899ddb56a9be496fb3a007f47");

        $order_taxes = $client->taxForOrder([
            'from_country' => 'US',
            'from_zip' => $destination ? $destination->zipcode:'',
            'from_state' => $destination ? $destination->state:'',
            'from_city' => $destination ? $destination->city:'',
            'from_street' => $destination ? $destination->address:'',
            'to_country' => 'US',
            'to_zip' => $destination ? $destination->zipcode:'',
            'to_state' => $destination ? $destination->state:'',
            'to_city' => $destination ? $destination->city:'',
            'to_street' => $destination ? $destination->address:'',
            'amount' => $price,
            'shipping' => $shipping,
        ]);

        return $order_taxes->amount_to_collect;
    }
}