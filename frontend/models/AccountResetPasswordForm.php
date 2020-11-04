<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * AccountResetPasswordForm is the model behind the contact form.
 */
class AccountResetPasswordForm extends Model{
    public $password;
    public $password_repeat;
    public $password_confirm;

    private $user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'string', 'min' => 6],
            ['password', 'compare', 'compareAttribute'=>'password_repeat', 'message'=>"Passwords don't match" ],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],

            ['password_confirm', 'string', 'min' => 6],
            ['password_confirm', 'validatePassword'],

            [['password', 'password_repeat', 'password_confirm'], 'required']
        ];
    }

    public function validatePassword($attribute){
        if( !$this->hasErrors() ){
            if( !$this->user->validatePassword($this->password_confirm) ){
                $this->addError($attribute, 'Current password is incorrect');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password'          => 'New Password',
            'password_repeat'   => 'New Password (repeat)',
            'password_confirm'  => 'Old Password'
        ];
    }

    public function init(){
        $this->user     = Yii::$app->user->identity;
    }

    public function save(){
        if( $this->validate() ){
            if( $this->password ){
                $this->user->setPassword($this->password);
            }
            return $this->user->save();
        }
        return false;
    }
}
