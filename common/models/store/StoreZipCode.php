<?php

namespace common\models\store;

use Yii;
use common\models\core\Store;
use common\models\store\query\StoreZipCodeQuery;

/**
 * This is the model class for table "store_zip_code".
 *
 * @property integer $id
 * @property integer $admin_id
 * @property integer $store_id
 * @property string $zip_code
 * @property integer $status
 * @property float $lat
 * @property float $lng
 */
class StoreZipCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_zip_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'store_id', 'status'], 'integer'],
            [['lat', 'lng'], 'safe'],
            [['zip_code'], 'string', 'max' => 55],
            [['zip_code'], 'unique'],
        ];
    }


    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'admin_id'  => 'Admin ID',
            'store_id'  => 'Store ID',
            'zip_code'  => 'Zip Code',
            'status'    => 'Status',
            'lat'       => 'Latitude',
            'lng'       => 'Longitude'
        ];
    }

    public function geolocate(){
        $api_key = Yii::$app->params['google']['geolocation'];

        if( !$this->lat
            && !$this->lng
            && $this->zip_code){

            $address = $this->zip_code;
            $url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=US";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response);

//            var_dump($response_a);
//            die();

            if( isset($response_a->results)
                && array_key_exists(0, $response_a->results)){
                $this->lat  = $response_a->results[0]->geometry->location->lat;
                $this->lng  = $response_a->results[0]->geometry->location->lng;
                if(!$this->save()){
                    var_dump($this->errors);
                }
            }

        }

    }

    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false )
    {
        $query =  new StoreZipCodeQuery(get_called_class());

        if( !$overRideScope )
            $query->store();

        return $query;
    }
}
