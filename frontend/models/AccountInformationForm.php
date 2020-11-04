<?php

namespace frontend\models;

use common\models\core\Subregions;
use common\models\customer\CustomerAddress;
use Yii;
use yii\base\Model;
use frontend\components\CurrentCustomer;

/**
 * AccountInformationForm is the model behind the contact form.
 */
class AccountInformationForm extends Model
{
    public $first_name;
    public $last_name;
    public $address_1;
    public $address_2;
    public $city;
    public $subregion;
    public $zipcode;
    public $same_as_shipping;
    public $billing_first_name;
    public $billing_last_name;
    public $billing_address_1;
    public $billing_address_2;
    public $billing_city;
    public $billing_subregion;
    public $billing_zipcode;

    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'address_1', 'address_2', 'city', 'billing_first_name', 'billing_last_name', 'billing_address_1', 'billing_address_2', 'billing_city'], 'string'],
            [['zipcode', 'subregion', 'billing_subregion', 'billing_zipcode'], 'integer'],
            [['first_name', 'last_name', 'address_1', 'city', 'subregion', 'zipcode', 'same_as_shipping'], 'required'],
     //       [['billing_first_name', 'billing_last_name', 'billing_address_1', 'billing_city', 'billing_subregion', 'billing_zipcode'], 'required'],
            [['same_as_shipping'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address_1' => 'Address',
            'address_2' => 'Apt/Suite',
            'city' => 'City',
            'subregion' => 'State',
            'zipcode' => 'Zip Code',
            'same_as_shipping' => 'Billing Same As Shipping',
            'billing_first_name' => 'First Name',
            'billing_last_name' => 'Last Name',
            'billing_address_1' => 'Address',
            'billing_address_2' => 'Apt/Suite',
            'billing_city' => 'City',
            'billing_subregion' => 'State',
            'billing_zipcode' => 'Zip Code'
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }

    public function init()
    {
        if ($user = CurrentCustomer::getCustomer()) {
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
        }
        if ($address = CurrentCustomer::getCustomerShippingAddress()) {
            $this->address_1 = $address->address_1;
            $this->address_2 = $address->address_2;
            $this->city = $address->city;
            $this->subregion = $address->subregion;
            $this->zipcode = $address->postcode;
            if ($billing_address = CurrentCustomer::getCustomerBillingAddress()) {
                if ($billing_address->address_id == $address->address_id) {
                    $this->same_as_shipping = 1;
                } else if ($billing_address->default_shipping === '0') {
                    $this->billing_address_1 = $billing_address->address_1;
                    $this->billing_address_2 = $billing_address->address_2;
                    $this->billing_city = $billing_address->city;
                    $this->billing_subregion = $billing_address->subregion;
                    $this->billing_zipcode = $billing_address->postcode;
                    $this->same_as_shipping = 0;
                }
            } else {
                $this->same_as_shipping = 0;
            }
        }
    }

    public function save()
    {
        if ($this->validate()) {
            $user = CurrentCustomer::getCustomer();
            $user->first_name = $this->first_name;
            $user->last_name = $this->last_name;

            $address = CurrentCustomer::getCustomerShippingAddress();
            if (!$address) {
                $address = new CustomerAddress();
                $address->customer_id = Yii::$app->user->id;
                $address->default_shipping = 1;
            }

            $address->firstname = $this->first_name;
            $address->lastname = $this->last_name;
            $address->address_1 = $this->address_1;
            $address->address_2 = $this->address_2 ? $this->address_2 : ' ';
            $address->postcode = $this->zipcode;
            $address->city = $this->city;
            $address->region_id = 840;
            $address->subregion_id = $this->subregion;

            if ($this->same_as_shipping === '1') {
                if ($billing_address = CurrentCustomer::getCustomerBillingAddress()) {
                    if ($billing_address->address_id !== $address->address_id) {
                        $billing_address->delete();
                    }
                }
                $address->default_shipping = 1;
                $address->default_billing = 1;
                //echo "<pre>";
                if ($user->save() && $address->save()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $address->default_shipping = 1;
                $address->default_billing = 0;
                $billing_address = CurrentCustomer::getCustomerBillingAddress();
                if (!$billing_address) {
                    $billing_address = new CustomerAddress();
                    $billing_address->customer_id = Yii::$app->user->id;
                }
                if ($billing_address->address_id === $address->address_id) {
                    $billing_address = new CustomerAddress();
                    $billing_address->customer_id = Yii::$app->user->id;
                }
                $billing_address->default_shipping = 0;
                $billing_address->default_billing = 1;
                $billing_address->firstname = $this->billing_first_name;
                $billing_address->lastname = $this->billing_last_name;
                $billing_address->address_1 = $this->billing_address_1;
                $billing_address->address_2 = $this->billing_address_2 ? $this->billing_address_2 : ' ';
                $billing_address->postcode = $this->billing_zipcode;
                $billing_address->city = $this->billing_city;
                $billing_address->region_id = 840;
                $billing_address->subregion_id = $this->billing_subregion;
                if ($user->save() && $address->save() && $billing_address->save()) {
                    return true;
                } else {
                    $errors = "";
                    if (!$address->validate()) {
                        foreach ($address->errors as $field) {
                            $errors .= "&bull; Shipping $field[0]<br>";
                        }
                    }
                    if (!$billing_address->validate()) {
                        foreach ($billing_address->errors as $field) {
                            $errors .= "&bull; Billing $field[0]<br>";
                        }
                    }

                    if (strlen($errors) > 0) {
                        return $errors;
                    } else {
                        return false;
                    }
                }
            }
        }
        return false;
    }
}
