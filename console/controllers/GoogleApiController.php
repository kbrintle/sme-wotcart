<?php

namespace console\controllers;

use common\models\store\StoreZipCode;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;


class GoogleApiController extends Controller{

    public function actionGeolocate(){
        $all_zip_codes = StoreZipCode::find()
            ->where([
                'lat' => NULL,
                'lng' => NULL
            ])
            ->all();

        foreach($all_zip_codes as $zip_code){
            echo $this->ansiFormat("Gelocating #$zip_code->id \n", Console::FG_GREEN);
            echo $this->ansiFormat($zip_code->geolocate(), Console::FG_GREEN);
        }

    }

}