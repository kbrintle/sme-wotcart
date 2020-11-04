<?php

namespace common\models\settings;

use common\components\CurrentStore;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "settings_store".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $logo
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zipcode
 * @property string $store_policy
 * @property string $homepage_title
 * @property string $homepage_text
 * @property string $who_text
 * @property string $about_text
 * @property string $sales_email
 * @property string Sgeneral_email
 * @property string $facebook_url
 * @property string $instagram_url
 * @property string $twitter_url
 * @property string $youtube_url
 * @property string $misc_header_scripts
 * @property string $misc_footer_scripts
 * @property string $misc_success_scripts
 * @property string $banner_type
 * @property string $banner_text
 * @property integer $created_at
 * @property integer $supervisor_active
 * @property string $supervisor_order_threshold
 * @property string $supervisor_email
 */
class SettingsStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'created_at', 'supervisor_active'], 'integer'],
            [['store_policy', 'address', 'phone', 'city', 'zipcode', 'state' , 'about_text', 'general_email', 'sales_email',  'who_text', 'homepage_title', 'homepage_text', 'misc_header_scripts', 'misc_footer_scripts', 'misc_success_scripts', 'banner_text'], 'string'],
            [['name', 'logo', 'banner_type'], 'string', 'max' => 55],
            [['supervisor_order_threshold'], 'safe'],
            [['supervisor_email', 'instagram_url','facebook_url', 'twitter_url', 'youtube_url'], 'string', 'max' => 255],
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
            'name' => 'Account Name',
            'facebook_url' => 'Facebook Url',
            'instagram_url' => 'Instagram Url',
            'store_policy' => 'Store Policy',
            'homepage_title' => 'Homepage Title',
            'homepage_text' => 'Homepage Content',
            'about_text' => 'About',
            'who_text' => 'Who We Are',
            'twitter_url' => 'Twitter Url',
            'youtube_url' => 'Youtube Url',
            'misc_header_scripts' => 'Misc Header Scripts',
            'misc_footer_scripts' => 'Misc Footer Scripts',
            'misc_success_scripts' => 'Checkout Success Scripts',
            'created_at' => 'Created Time',
        ];
    }



    /**
     * @inheritdoc
     * @return \common\models\settings\query\SettingsStoreQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {
        $query =  new \common\models\settings\query\SettingsStoreQuery(get_called_class());

        if( ! $overRideScope )
            $query->store();

        return $query;
    }

    public static function getSettings(){
        return self::find()->where(['store_id'=>[CurrentStore::getStoreId(), 0]])->one();
    }

    public static function getLogo($fullPath = false){
        $settings = self::find()->where(['store_id'=>CurrentStore::getStoreId()])->one();

        if(!isset($settings->logo) || empty($settings->logo)){
            $settings = self::find()->where(['store_id'=>0])->one();
        }

        if(empty($settings->logo)){
            $settings->logo = '/logos/SME-Logo-2018TL_V1.png';
        }
        return ($fullPath) ? 'https://www.smeincusa.com/uploads'.$settings->logo : $settings->logo;
    }
}