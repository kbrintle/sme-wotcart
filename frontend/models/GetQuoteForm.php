<?php

namespace frontend\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class GetQuoteForm extends Model
{
    public $name;
    public $clinic;
    public $email;
    public $phone;
    public $notes;
    public $product;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'clinic', 'email', 'phone'], 'required'],
            ['email', 'email'],
            [['notes', 'product'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'clinic' => 'Clinic Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'notes' => 'Additional Information',
        ];
    }

}
