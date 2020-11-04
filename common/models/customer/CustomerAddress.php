<?php

namespace common\models\customer;
use common\models\core\CountryRegion;
use common\models\core\Regions;
use common\models\core\Subregions;

/**
 * This is the model class for table "customer_address".
 *
 * @property integer $address_id
 * @property integer $customer_id
 * @property string $type
 * @property string $firstname
 * @property string $lastname
 * @property string $suffix
 * @property string $company
 * @property string $address_1
 * @property string $address_2
 * @property string $city
 * @property string $postcode
 * @property string $region
 * @property integer $region_id
 * @property integer $subregion_id
 * @property integer $phone
 * @property integer $fax
 * @property integer $default_billing
 * @property integer $default_shipping
 */
class CustomerAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'firstname', 'lastname', 'address_1', 'city', 'postcode'], 'required'],
            [['customer_id', 'subregion_id', 'region_id'], 'integer'],
            [['custom_field'], 'string'],
            [['firstname', 'lastname', 'suffix', 'phone','fax'], 'string', 'max' => 255],
            [['company'], 'string', 'max' => 40],
            [['address_1', 'address_2', 'city', 'type'], 'string', 'max' => 255],
            [['postcode'], 'string', 'max' => 10],
            [['default_billing', 'default_shipping'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => 'Address ID',
            'customer_id' => 'Customer ID',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'suffix' => 'suffix',
            'company' => 'Company',
            'address_1' => 'Address',
            'address_2' => 'Address 2',
            'city' => 'City',
            'postcode' => 'Zip/Postcode',
            'region_id' => 'Country',
            'subregion_id' => 'State/Province',
            'default_billing' => 'Default Billing',
            'default_shipping' => 'Default Shipping',
            'phone' => 'Phone',
            'fax' => 'Fax',
        ];
    }

    public function getRegion()
    {
        return $this->hasOne(CountryRegion::className(), ['id' => 'region_id']);
    }

    public function getRegionById($id)
    {
        return CountryRegion::find()
            ->where(['id' => $id])
            ->one();
    }

    public function getRegionByFips($fips)
    {
        return Regions::find()
            ->where(['fips' => $fips])
            ->one();
    }

    public function getSubregion()
    {
        return $this->hasOne(Subregions::className(), ['id' => 'subregion_id']);
    }


    public function getSubRegionById($id)
    {
        return Subregions::find()
            ->where(['id' => $id])
            ->one();
    }
}