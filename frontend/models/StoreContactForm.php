<?php

namespace frontend\models;

use common\components\CurrentStore;
use common\models\store\StoreLocation;
use Yii;
use yii\base\Model;
use common\components\Notify;

/**
 * ContactForm is the model behind the contact form.
 */
class StoreContactForm extends Model
{
    public $name;
    public $email;
    public $body;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            //['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
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
        $storeEmail = StoreLocation::find()->where(['store_id'=>CurrentStore::getStoreId()])->one()->email;

        Notify::sendMail("New Store Contact Message", [$storeEmail], 'customer/store-contact', $data=[
            'model'=>$this
        ]);

        return true;

    }
}
