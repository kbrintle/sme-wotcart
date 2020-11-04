<?php
/**
 * Created by PhpStorm.
 * User: jake
 * Date: 4/19/17
 * Time: 2:43 PM
 */

namespace common\models\utilities;

use Yii;
use yii\base\Model;

class Mailchimp extends Model
{

    private $api_key;
    private $list_id;
    private $data_center;

    private $request_url;
    private $request_json;


    /**
     * @param null $params
     */
    public function init()
    {
        $this->api_key      = \Yii::$app->params['mailchimp']['api_key'];
        $this->list_id      = \Yii::$app->params['mailchimp']['list_id'];
        $this->data_center  = \Yii::$app->params['mailchimp']['data_center'];
    }

    private function createRequestUrl($resource_url)
    {
        $url = 'https://' . $this->data_center . '.api.mailchimp.com/3.0/' . $resource_url;
        return $url;
    }

    private function sendRequest()
    {
        $ch = curl_init($this->request_url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request_json);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }


    public function importUserToList($user = null)
    {
        if($user){
            $this->request_json = json_encode([
                'email_address' => $user->email,
                'status' => 'subscribed' // "subscribed","unsubscribed","cleaned","pending"
//                'merge_fields' => [
//                    'FNAME' => $user->first_name,
//                    'LNAME' => $user->last_name
//                ]
            ]);
            $this->request_url = $this->createRequestUrl('lists/' . $this->list_id . '/members/');

            return $this->sendRequest();
        }else{
            die();
        }

    }
}