<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "settings_shipping".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $shipping_method_id
 * @property string $flat_rate_fee
 * @property integer $free_shipping
 * @property string $free_shipping_min
 */
class SettingsShipping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_shipping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'shipping_method_id', 'free_shipping'], 'integer'],
            [['flat_rate_fee', 'free_shipping_min'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'shipping_method_id' => 'Shipping Method ID',
            'flat_rate_fee' => 'Flat Rate Fee',
            'free_shipping' => 'Free Shipping',
            'free_shipping_min' => 'Free Shipping Min',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\settings\query\SettingsShippingQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {
        $query =  new \common\models\settings\query\SettingsShippingQuery(get_called_class());

        if( ! $overRideScope )
            $query->store();

        return $query;
    }
}