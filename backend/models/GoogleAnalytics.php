<?php

namespace backend\models;

use Google_Client;
use Google_Service_Analytics;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

class GoogleAnalytics extends Model{

    public $access_token;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['access_token'], 'string']
        ];
    }

    public static function getAccessToken(){
        $service_account_json = __DIR__ . '/../assets/e5db86bcba04.json';
        putenv("GOOGLE_APPLICATION_CREDENTIALS=$service_account_json");
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Google_Service_Analytics::ANALYTICS);
        $client->fetchAccessTokenWithAssertion();

        $access_token = $client->getAccessToken();

        return $access_token ? $access_token['access_token'] : null;
    }

    public static function getGAView(){
        return '54131496';  //@TODO make this dynamic
    }

}