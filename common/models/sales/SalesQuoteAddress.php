<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "sales_quote_address".
 *
 * @property integer $id
 * @property integer $quote_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $customer_id
 * @property integer $save_in_address_book
 * @property integer $customer_address_id
 * @property string $address_type
 * @property string $email
 * @property string $prefix
 * @property string $firstname
 * @property string $middlename
 * @property string $lastname
 * @property string $suffix
 * @property string $company
 * @property string $street
 * @property string $city
 * @property string $region
 * @property integer $region_id
 * @property string $postcode
 * @property string $country_id
 * @property string $telephone
 * @property string $fax
 * @property integer $same_as_billing
 */
class SalesQuoteAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_quote_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quote_id', 'customer_id', 'save_in_address_book', 'customer_address_id', 'region_id', 'same_as_billing'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['address_type', 'email', 'firstname', 'lastname', 'company', 'street', 'city', 'region', 'postcode', 'country_id', 'telephone', 'fax'], 'string', 'max' => 255],
            [['prefix', 'middlename', 'suffix'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quote_id' => 'Quote ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'customer_id' => 'Customer ID',
            'save_in_address_book' => 'Save In Address Book',
            'customer_address_id' => 'Customer Address ID',
            'address_type' => 'Address Type',
            'email' => 'Email',
            'prefix' => 'Prefix',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'suffix' => 'Suffix',
            'company' => 'Company',
            'street' => 'Street',
            'city' => 'City',
            'region' => 'Region',
            'region_id' => 'Region ID',
            'postcode' => 'Postcode',
            'country_id' => 'Country ID',
            'telephone' => 'Telephone',
            'fax' => 'Fax',
            'same_as_billing' => 'Same As Billing',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesQuoteAddressQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesQuoteAddressQuery(get_called_class());
    }
}
