<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "lead".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $practitioner_name
 * @property string $clinic_name
 * @property string $clinic_position
 * @property string $ordering_contact_name
 * @property string $clinic_address
 * @property string $clinic_city
 * @property string $clinic_state
 * @property string $clinic_zip
 * @property string $clinic_phone
 * @property string $clinic_fax
 * @property string $clinic_email
 * @property string $contact_email
 * @property string $website
 * @property string $network_member_list
 * @property string $how_hear
 * @property string $top_five
 * @property integer $is_deleted
 * @property integer $created_at
 */
class Lead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lead';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_deleted', 'created_at', 'store_id'], 'integer'],
            [['practitioner_name', 'clinic_name', 'clinic_position', 'ordering_contact_name', 'clinic_address', 'clinic_city', 'clinic_state', 'clinic_zip', 'clinic_phone', 'clinic_fax', 'clinic_email', 'contact_email', 'website', 'network_member_list', 'how_hear', 'top_five'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'practitioner_name' => 'Practitioner Name',
            'clinic_name' => 'Clinic Name',
            'clinic_position' => 'Clinic Position',
            'ordering_contact_name' => 'Ordering Contact Name',
            'clinic_address' => 'Clinic Address',
            'clinic_city' => 'Clinic City',
            'clinic_state' => 'Clinic State',
            'clinic_zip' => 'Clinic Zip',
            'clinic_phone' => 'Clinic Phone',
            'clinic_fax' => 'Clinic Fax',
            'clinic_email' => 'Clinic Email',
            'contact_email' => 'Contact Email',
            'website' => 'Website',
            'network_member_list' => 'Network Member List',
            'how_hear' => 'How Hear',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\store\customer\query\LeadQuery the active query used by this AR class.
     */

    public static function find()
    {
        return new \common\models\customer\query\LeadQuery(get_called_class());
    }

    public static function getNewLeadsCount(){
        return self::find()

            ->where([
                'approved' => 1,
                'ignored' => 0
            ])->count();


    }
    public function getState()
    {
        return $this->hasOne(CountryRegion::className(), ['id' => 'region_id']);
    }

    public function convert(){
        $customer = new Customer();

        $customer->store_id = $this->store_id;
        $customer->contact_email = $this->email;

        if($customer->save()){
            $customerAddress = new CustomerAddress();
            $customerAddress->address_1    = $this->clinic_address;
            $customerAddress->city         = $this->clinic_city;
            $customerAddress->subregion_id = $this->clinic_state;
            $customerAddress->postcode     = $this->clinic_zip;
            $customerAddress->phone        = $this->clinic_phone;
        }
    }
}