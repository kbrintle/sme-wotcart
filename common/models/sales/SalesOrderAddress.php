<?php

namespace common\models\sales;

use common\models\core\CountryRegion;
use common\models\core\Subregions;
use common\models\sales\query\SalesOrderAddressQuery;
use Yii;

/**
 * This is the model class for table "sales_order_address".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $customer_address_id
 * @property integer $quote_address_id
 * @property integer $subregion_id
 * @property integer $customer_id
 * @property string $fax
 * @property string $postcode
 * @property string $lastname
 * @property string $street
 * @property string $street2
 * @property string $city
 * @property string $email
 * @property string $telephone
 * @property string $region_id
 * @property string $firstname
 * @property string $address_type
 * @property string $prefix
 * @property string $middlename
 * @property string $suffix
 * @property string $company
 * @property string $vat_id
 * @property integer $vat_is_valid
 * @property string $vat_request_id
 * @property string $vat_request_date
 * @property integer $vat_request_success
 */
class SalesOrderAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'customer_address_id', 'quote_address_id', 'region_id', 'subregion_id', 'vat_is_valid', 'vat_request_success'], 'integer'],
            [['vat_id', 'vat_request_id', 'vat_request_date'], 'string'],
            [['fax', 'postcode', 'lastname', 'street', 'street2', 'city', 'email', 'telephone', 'firstname', 'address_type', 'prefix', 'middlename', 'suffix', 'company'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'customer_address_id' => 'Customer Address ID',
            'quote_address_id' => 'Quote Address ID',
            'region_id' => 'Region ID',
            'customer_id' => 'Customer ID',
            'fax' => 'Fax',
            'postcode' => 'Postcode',
            'lastname' => 'Lastname',
            'street' => 'Street',
            'city' => 'City',
            'email' => 'Email',
            'telephone' => 'Telephone',
            'subregion_id' => 'Subregion ID',
            'firstname' => 'Firstname',
            'address_type' => 'Address Type',
            'prefix' => 'Prefix',
            'middlename' => 'Middlename',
            'suffix' => 'Suffix',
            'company' => 'Company',
            'vat_id' => 'Vat ID',
            'vat_is_valid' => 'Vat Is Valid',
            'vat_request_id' => 'Vat Request ID',
            'vat_request_date' => 'Vat Request Date',
            'vat_request_success' => 'Vat Request Success',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesOrderAddressQuery(get_called_class());
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    public function getSubregion()
    {
        return $this->hasOne(Subregions::className(), ['id' => 'subregion_id']);
    }
    public function getCountryRegion()
    {
        return $this->hasOne(CountryRegion::className(), ['id' => 'region_id']);
    }

    public function getRegionName($abbrev=false){
        $subregion = $this->region;
        if( $subregion )
            return $abbrev ? $subregion->code : $subregion->default_name;
        return null;
    }

    public function getSubregionName($abbrev=false){
        $subregion = $this->subregion;
        if( $subregion )
            return $abbrev ? $subregion->abbreviation : $subregion->name;
        return null;
    }
}