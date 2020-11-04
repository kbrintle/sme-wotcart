<?php

namespace frontend\models;

use yii\base\Model;
use common\components\Notify;

/**
 * ContactForm is the model behind the contact form.
 */
class ClinicSpotlightForm extends Model
{
    public $clinic_name;
    public $clinic_address;
    public $locations;
    public $response;
    public $social;
    public $social_other;
    public $sending;
    public $sending_other;
    public $contact_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clinic_name', 'sending', 'clinic_address', 'locations', 'response', 'social', 'contact_name'], 'required'],
            [['sending', 'sending_other', 'contact_name', 'social_other'], 'safe'],
        ];
    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($data)
    {
        $subject = "SME - Clinic Spotlight Submission";
        $results = Notify::sendNotification(['info@smeincusa.com'=>'SME Info'], $subject , 'd-d203a066a57140d0b2a5c2571e30fc12', $data);

        return true;

    }
}
