<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "settings_payment".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $cardconnect_enabled
 * @property integer $stripe_enabled
 * @property integer $stripe_test_mode
 * @property string $stripe_title
 * @property string $stripe_test_secret_key
 * @property string $stripe_test_publishable_key
 * @property string $stripe_live_secret_key
 * @property string $stripe_live_publishable_key
 * @property string $stripe_new_order_status
 * @property string $stripe_payment_countries
 * @property integer $paypal_enabled
 * @property string $paypal_client_id
 * @property string $paypal_client_secret
 * @property string $purchase_order_enabled
 * @property integer $shipping_method_id
 * @property integer $flat_rate_fee
 * @property integer $free_ship
 * @property integer $free_ship_min
 * @property integer $calculate_tax
 * @property integer $paypal_sandbox_mode
 */
// * @property integer $stripe_enabled
// * @property integer $stripe_test_mode
// * @property integer $stripe_title
// * @property integer $stripe_test_secret_key
// * @property integer $stripe_test_publishable_key
// * @property integer $stripe_live_secret_key
// * @property integer $stripe_live_publishable_key
// * @property integer $stripe_new_order_status
// * @property integer $paypal_enabled
// * @property integer $paypal_client_id
// * @property integer $paypal_client_secret

class SettingsPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'purchase_order_enabled',
                'stripe_enabled',
                'cardconnect_enabled',
                'stripe_test_mode',
                'paypal_enabled',
                'paypal_sandbox_mode',
                'calculate_tax'
            ], 'boolean'],
            [[
                'stripe_title',
                'stripe_test_secret_key',
                'stripe_test_publishable_key',
                'stripe_live_secret_key',
                'stripe_live_publishable_key',
                'stripe_new_order_status',
                'stripe_payment_countries',
                'paypal_client_id',
                'paypal_client_secret',
            ], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                          => 'ID',
            'calculate_tax'               => 'Calculate Tax',
            'stripe_enabled'              => 'Enabled',
            'stripe_test_mode'            => 'Test Mode',
            'stripe_title'                => 'Title',
            'stripe_test_secret_key'      => 'Test Secret Key',
            'stripe_test_publishable_key' => 'Test Publishable Key',
            'stripe_live_secret_key'      => 'Live Secret Key',
            'stripe_live_publishable_key' => 'Live Publishable Key',
            'stripe_new_order_status'     => 'New Order Status',
            'stripe_payment_countries'    => 'Payment Countries',
            'paypal_enabled'              => 'Enabled',
            'paypal_sandbox_mode'         => 'Sandbox Mode',
            'paypal_client_id'            => 'Client ID',
            'paypal_client_secret'        => 'Client Secret',
            'purchase_order_enabled'      => 'Purchase Order Enabled'
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\settings\query\SettingsPaymentQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {
        $query =  new \common\models\settings\query\SettingsPaymentQuery(get_called_class());

        if( ! $overRideScope )
            $query->store();

        return $query;
    }

/*    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = $value;
        } else  {

            throw new InvalidArgumentException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }*/
}