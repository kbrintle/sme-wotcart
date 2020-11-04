<?php

namespace common\models\store;

use Yii;
use common\components\CurrentStore;
use common\models\store\query\StoreLocationQuery;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $address
 * @property string $alt_address
 * @property string $city
 * @property string $country
 * @property string $zipcode
 * @property string $state
 * @property integer $state_id
 * @property string $email
 * @property string $phone
 * @property string $fax
 * @property string $description
 * @property string $hours
 * @property integer $is_active
 * @property integer $sort
 * @property string $link
 * @property string $latitude
 * @property string $longtitude
 * @property integer $zoom_level
 * @property string $image_icon
 */
class StoreLocation extends \yii\db\ActiveRecord
{

    public $us_state_abbreviations = array(
        'AL'=>'ALABAMA',
        'AK'=>'ALASKA',
        'AS'=>'AMERICAN SAMOA',
        'AZ'=>'ARIZONA',
        'AR'=>'ARKANSAS',
        'CA'=>'CALIFORNIA',
        'CO'=>'COLORADO',
        'CT'=>'CONNECTICUT',
        'DE'=>'DELAWARE',
        'DC'=>'DISTRICT OF COLUMBIA',
        'FM'=>'FEDERATED STATES OF MICRONESIA',
        'FL'=>'FLORIDA',
        'GA'=>'GEORGIA',
        'GU'=>'GUAM GU',
        'HI'=>'HAWAII',
        'ID'=>'IDAHO',
        'IL'=>'ILLINOIS',
        'IN'=>'INDIANA',
        'IA'=>'IOWA',
        'KS'=>'KANSAS',
        'KY'=>'KENTUCKY',
        'LA'=>'LOUISIANA',
        'ME'=>'MAINE',
        'MH'=>'MARSHALL ISLANDS',
        'MD'=>'MARYLAND',
        'MA'=>'MASSACHUSETTS',
        'MI'=>'MICHIGAN',
        'MN'=>'MINNESOTA',
        'MS'=>'MISSISSIPPI',
        'MO'=>'MISSOURI',
        'MT'=>'MONTANA',
        'NE'=>'NEBRASKA',
        'NV'=>'NEVADA',
        'NH'=>'NEW HAMPSHIRE',
        'NJ'=>'NEW JERSEY',
        'NM'=>'NEW MEXICO',
        'NY'=>'NEW YORK',
        'NC'=>'NORTH CAROLINA',
        'ND'=>'NORTH DAKOTA',
        'MP'=>'NORTHERN MARIANA ISLANDS',
        'OH'=>'OHIO',
        'OK'=>'OKLAHOMA',
        'OR'=>'OREGON',
        'PW'=>'PALAU',
        'PA'=>'PENNSYLVANIA',
        'PR'=>'PUERTO RICO',
        'RI'=>'RHODE ISLAND',
        'SC'=>'SOUTH CAROLINA',
        'SD'=>'SOUTH DAKOTA',
        'TN'=>'TENNESSEE',
        'TX'=>'TEXAS',
        'UT'=>'UTAH',
        'VT'=>'VERMONT',
        'VI'=>'VIRGIN ISLANDS',
        'VA'=>'VIRGINIA',
        'WA'=>'WASHINGTON',
        'WV'=>'WEST VIRGINIA',
        'WI'=>'WISCONSIN',
        'WY'=>'WYOMING',
        'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
        'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
        'AP'=>'ARMED FORCES PACIFIC'
    );

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'hours', 'address', 'city', 'state', 'zipcode', 'name'], 'required'],
            [['store_id', 'state_id', 'is_active', 'sort', 'zoom_level'], 'integer'],
            [['description', 'hours'], 'string'],
            [['name', 'slug', 'address', 'alt_address', 'city', 'country', 'state', 'email', 'link', 'image_icon'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 55],
            [['zipcode', 'phone', 'fax'], 'string', 'max' => 25],
            [['latitude', 'longtitude'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'name' => 'Name',
            'slug' => 'slug',
            'type' => 'Type',
            'address' => 'Address',
            'alt_address' => 'Alt Address',
            'city' => 'City',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
            'state' => 'State',
            'state_id' => 'State ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'description' => 'Description',
            'hours' => 'Hours',
            'is_active' => 'Is Active',
            'sort' => 'Sort',
            'link' => 'Link',
            'latitude' => 'Latitude',
            'longtitude' => 'Longtitude',
            'zoom_level' => 'Zoom Level',
            'image_icon' => 'Image Icon',
        ];
    }

    public function getStore()
    {
        return $this->hasOne(\common\models\core\Store::className(), ['id' => 'store_id']);
    }


    /**
     * @inheritdoc
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find( $overRideScope = false )
    {
        $query = new StoreLocationQuery(get_called_class());

        if( ! $overRideScope && CurrentStore::getStoreId() > 0 )
            $query->store();

        return $query;
    }

    public static function normalFind(){
        return new StoreLocationQuery(get_called_class());
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if( parent::beforeSave($insert) ){
            if($this->address
                && $this->city
                && $this->state){
                $address = $this->address.", ".$this->city.", ".$this->state;
                $address = str_replace(' ', '+', trim($address) );
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
                if( isset($response_a->results) && array_key_exists(0, $response_a->results) && empty($this->longtitude) && empty($this->latitude)){
                    $this->latitude     = $response_a->results[0]->geometry->location->lat;
                    $this->longtitude   = $response_a->results[0]->geometry->location->lng;
                }
            }

            return true;
        }else{
            return false;
        }
    }

    public function getGoogleDestinationAddress(){
        return urlencode($this->address."+".$this->city."+".$this->state."+".$this->zipcode);
    }

}