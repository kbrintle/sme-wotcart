<?php

namespace frontend\models;

use common\components\CurrentStore;
use yii\base\Model;
use common\models\customer\Customer;
use common\models\customer\Lead;

/**
 * RegisterForm form
 */
class RegisterForm extends Model
{
    public $practitioner_name;
    public $clinic_name;
    public $clinic_position;
    public $ordering_contact_name;
    public $clinic_address;
    public $clinic_city;
    public $clinic_state;
    public $clinic_zip;
    public $clinic_phone;
    public $clinic_fax;
    public $clinic_email;
    public $contact_email;
    public $website;
    public $top_five;
    public $network_member_list;
    public $how_hear;

    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['contact_email', 'trim'],
            [['contact_email', 'clinic_email'], 'email'],
            [['clinic_phone', 'clinic_fax'], 'match', 'pattern' => '^(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$^', 'message' => \Yii::t('app', 'Not a valid phone number')],
            ['clinic_zip', 'match', 'pattern' => '/^[0-9]{5}(-[0-9]{4})?$/', 'message' => \Yii::t('app', 'Not a valid US zip code')],
            [['practitioner_name', 'clinic_name', 'clinic_position', 'ordering_contact_name', 'clinic_address', 'clinic_city', 'clinic_state', 'clinic_zip', 'clinic_phone', 'clinic_email', 'contact_email'], 'required'],
            [['practitioner_name', 'clinic_name', 'clinic_position', 'ordering_contact_name', 'clinic_address', 'clinic_city', 'clinic_state', 'clinic_zip', 'clinic_phone', 'clinic_fax', 'clinic_email', 'contact_email', 'network_member_list'], 'string', 'max' => 255],
            [['top_five', 'website'], 'safe'],
            [[], \himiklab\yii2\recaptcha\ReCaptchaValidator::className()],

        ];
    }


    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $user_email = Customer::find()
                ->where([
                    'email' => $this->email
                ])
                ->one();
            if ($user_email) {
                $this->addError($attribute, 'Email is already taken');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Lead();
        $user->practitioner_name = $this->practitioner_name;
        $user->clinic_name = $this->clinic_name;
        $user->store_id = CurrentStore::getStoreId();
        $user->clinic_position = $this->clinic_position;
        $user->ordering_contact_name = $this->ordering_contact_name;
        $user->clinic_address = $this->clinic_address;
        $user->clinic_city = $this->clinic_city;
        $user->clinic_state = $this->clinic_state;
        $user->clinic_zip = $this->clinic_zip;
        $user->clinic_phone = $this->clinic_phone;
        $user->clinic_fax = $this->clinic_fax;
        $user->clinic_email = $this->clinic_email;
        $user->contact_email = $this->clinic_email;
        $user->website = $this->website;
        $user->top_five = implode(',', $this->top_five);
        $user->network_member_list = $this->network_member_list;
        $user->how_hear = $this->how_hear;
        $user->created_at = time();

//        $user->setPassword($this->password);
//        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
