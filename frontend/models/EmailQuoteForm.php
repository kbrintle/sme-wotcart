<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\components\Notify;

/**
 * ContactForm is the model behind the contact form.
 */
class EmailQuoteForm extends Model
{

    public $email;
    public $body;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
        ];
    }

   /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        Notify::sendMail(Yii::$app->name ." Quote", [$this->email], 'customer/quote', ['model'=>$this]);

        return true;

    }
}
