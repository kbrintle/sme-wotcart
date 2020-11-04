<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "sales_order_option".
 *
 * @property integer $order_option_id
 * @property integer $order_id
 * @property integer $order_item_id
 * @property integer $product_option_id
 * @property integer $product_option_value_id
 * @property string $name
 * @property string $value
 * @property string $type
 */
class SalesOrderOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_product_id', 'product_option_id', 'name', 'value', 'type'], 'required'],
            [['order_id', 'order_product_id', 'product_option_id', 'product_option_value_id'], 'integer'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_option_id' => 'Order Option ID',
            'order_id' => 'Order ID',
            'order_item_id' => 'Order Product ID',
            'product_option_id' => 'Product Option ID',
            'product_option_value_id' => 'Product Option Value ID',
            'name' => 'Name',
            'value' => 'Value',
            'type' => 'Type',
        ];
    }
}
