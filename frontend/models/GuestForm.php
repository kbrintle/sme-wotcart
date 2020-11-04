<?php
namespace frontend\models;

use common\components\CurrentStore;
use Yii;
use yii\base\Model;
use common\models\customer\Customer;

/**
 * RegisterForm form
 */
class GuestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255]
        ];
    }

    public function setSession(){
        if( $this->validate() ){
            $session = Yii::$app->session;
            $session->set('guest_email', $this->email);
            return true;
        }

        return null;
    }

    public function getSession(){
        $session = Yii::$app->session;
        return $session->get('guest_email');
    }
}
