<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "catalog_product_option".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property string $price Price
 * @property string $cost Cost
 *
 */

class ShippingTableRate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping_tablerate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','store_id'], 'integer'],
            [['price','cost'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id'=>'Store ID',
            'price' => 'Price',
            'cost' => 'Cost'
        ];
    }
}