<?php

namespace frontend\models;

use common\models\utilities\Mailchimp;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class NewsletterSignupForm extends Model
{
    public $email;

    private $_messages = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * Message GETTERS
     */
    public function getSuccessMessage(){
        return array_key_exists('success', $this->_messages) ? $this->_messages['success'] : null;
    }
    public function getErrorMessage(){
        return array_key_exists('error', $this->_messages) ? $this->_messages['error'] : null;
    }


    /**
     * Message SETTERS
     */
    public function setSuccessMessage($message){
        $this->_messages['success'] = $message;
    }
    public function setErrorMessage($message){
        $this->_messages['error'] = $message;
    }


    /**
     * Save email address to Mailchimp and set success/error messages
     * @return bool
     */
    public function save(){
        $this->setErrorMessage(null);   //reset error message
        $this->setSuccessMessage(null); //reset success message

        $mailchimp = new Mailchimp();   //create new Mailchimp object
        if( $mailchimp->importUserToList($this) ){  //attempt to import user to list (defined in config/params)
            $this->email = null;    //reset email
            $this->setSuccessMessage('Your email was successfully added');  //set success message
            return true;    //return true (good)
        }

        $this->setErrorMessage('Something went horribly wrong');    //set error message
        return null;    //return null (bad)
    }
}
