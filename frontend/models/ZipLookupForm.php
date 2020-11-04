<?php

namespace frontend\models;

use common\models\core\Store;
use common\models\store\StoreLocation;
use common\models\store\StoreZipCode;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ZipLookupForm extends Model
{
    public $zip;
    public $lat;
    public $lng;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['zip'], 'required'],
            [['lat', 'lng'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zip' => 'Zip'
        ];
    }


    public function findStoreByZip(){
        if($this->zip){
            $storeZipCode = StoreZipCode::find()
                ->where([
                    'zip_code' => $this->zip
                ])
                ->one();

            if($storeZipCode){
                return $storeZipCode->store;
            }
        }
        return false;
    }


    public function getGeolocationByZip(){
        $address = $this->zip;
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=US";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        if( isset($response_a->results)
            && array_key_exists(0, $response_a->results)){
            $this->lat  = $response_a->results[0]->geometry->location->lat;
            $this->lng  = $response_a->results[0]->geometry->location->lng;
//            return $this->save();
        }
    }


    public function findStoresByZip(){
        $this->getGeolocationByZip();

        //debug, since we've exceeded the google API limit
//        $this->lat = 35.5951;
//        $this->lng = -82.5515;

        $connection = Yii::$app->getDb();
        //3959
        $calc_val = 3959;
        $command = $connection->createCommand("
          SELECT *, ( $calc_val * acos( cos( radians($this->lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($this->lng) ) + sin( radians($this->lat) ) * sin( radians( lat ) ) ) ) AS distance 
          FROM store_zip_code 
          GROUP BY (store_id)
          HAVING distance < 1000000 
          ORDER BY distance 
          LIMIT 0 , 3;
        ");
        $all_zip_codes = $command->queryAll();

        $output = [];
        foreach($all_zip_codes as $zip_code){
            $store = Store::findOne($zip_code['store_id']);
            $zip_code['store'] = $store;
            array_push($output, $zip_code);
        }

        return $output;
    }

    public function findClosestStore(){
        $this->getGeolocationByZip();

        //debug, since we've exceeded the google API limit
        $this->lat = 35.5951;
        $this->lng = -82.5515;

        $connection = Yii::$app->getDb();
        //3959
        $calc_val = 3959;
        $command = $connection->createCommand("
          SELECT *, ( $calc_val * acos( cos( radians($this->lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($this->lng) ) + sin( radians($this->lat) ) * sin( radians( lat ) ) ) ) AS distance 
          FROM store_zip_code 
          GROUP BY (store_id)
          HAVING distance < 1000000 
          ORDER BY distance 
          LIMIT 0 , 1;
        ");
        $closest_to_zip = $command->queryAll();

        if( count($closest_to_zip) > 0 ){
            $store_id = $closest_to_zip[0]['store_id'];
            $store = Store::findOne($store_id);
            return $store;
        }
        return null;
    }

}