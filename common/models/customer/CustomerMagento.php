<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_magento".
 *
 * @property int $id
 * @property string $store
 * @property string $website
 * @property string $group
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property string $suffix
 * @property string $customer_activated
 * @property string $reward_points_notification
 * @property string $billing_firstname
 * @property string $billing_lastname
 * @property string $billing_street_full
 * @property string $billing_street1
 * @property string $billing_street2
 * @property string $billing_city
 * @property string $billing_region
 * @property string $billing_country
 * @property string $billing_postcode
 * @property string $billing_telephone
 * @property string $billing_company
 * @property string $billing_fax
 * @property string $shipping_firstname
 * @property string $shipping_lastname
 * @property string $shipping_street_full
 * @property string $shipping_street1
 * @property string $shipping_street2
 * @property string $shipping_city
 * @property string $shipping_region
 * @property string $shipping_country
 * @property string $shipping_postalcode
 * @property string $shipping_telephone
 * @property string $shipping_company
 * @property string $shipping_fax
 * @property string $sales_rep
 * @property int $skip
 */
class CustomerMagento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_magento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store', 'group', 'website', 'email', 'firstname', 'lastname', 'suffix', 'customer_activated', 'reward_points_notification', 'billing_firstname', 'billing_lastname', 'billing_street_full', 'billing_street1', 'billing_street2', 'billing_city', 'billing_region', 'billing_country', 'billing_postcode', 'billing_telephone', 'billing_company', 'billing_fax', 'shipping_firstname', 'shipping_lastname', 'shipping_street_full', 'shipping_street1', 'shipping_street2', 'shipping_city', 'shipping_region', 'shipping_country', 'shipping_postalcode', 'shipping_telephone', 'shipping_company', 'shipping_fax', 'sales_rep'], 'string', 'max' => 155],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store' => 'Store',
            'email' => 'Email',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'suffix' => 'Suffix',
            'customer_activated' => 'Customer Activated',
            'reward_points_notification' => 'Reward Points Notification',
            'billing_firstname' => 'Billing Firstname',
            'billing_lastname' => 'Billing Lastname',
            'billing_street_full' => 'Billing Street Full',
            'billing_street1' => 'Billing Street1',
            'billing_street2' => 'Billing Street2',
            'billing_city' => 'Billing City',
            'billing_region' => 'Billing Region',
            'billing_country' => 'Billing Country',
            'billing_postcode' => 'Billing Postcode',
            'billing_telephone' => 'Billing Telephone',
            'billing_company' => 'Billing Company',
            'billing_fax' => 'Billing Fax',
            'shipping_firstname' => 'Shipping Firstname',
            'shipping_lastname' => 'Shipping Lastname',
            'shipping_street_full' => 'Shipping Street Full',
            'shipping_street1' => 'Shipping Street1',
            'shipping_street2' => 'Shipping Street2',
            'shipping_city' => 'Shipping City',
            'shipping_region' => 'Shipping Region',
            'shipping_country' => 'Shipping Country',
            'shipping_postalcode' => 'Shipping Postalcode',
            'shipping_telephone' => 'Shipping Telephone',
            'shipping_company' => 'Shipping Company',
            'shipping_fax' => 'Shipping Fax',
            'sales_rep' => 'Sales Rep',
        ];
    }
}