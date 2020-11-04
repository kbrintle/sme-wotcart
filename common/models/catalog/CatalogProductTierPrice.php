<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product_option".
 *
 * @property int $id ID
 * @property int $store_id Store ID
 * @property int $product_id Product ID
 * @property int $all_groups All Groups
 * @property int $customer_group_id Customer Group Id
 * @property string $qty Qty
 * @property string $value Value
 * @property string $sku Value
 * @property int found Value
 *
 */

class CatalogProductTierPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_tier_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','store_id','product_id','all_groups','customer_group_id', 'found' ], 'integer'],
            [['sku' ], 'string'],
            [['qty','value'], 'number']
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
            'product_id' => 'Product ID',
            'all_group' => 'All Group',
            'customer_group_id' => 'Customer Group ID',
            'qty' => 'Qty',
            'value' => 'Value'
        ];
    }
}