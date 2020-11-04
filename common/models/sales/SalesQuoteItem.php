<?php

namespace common\models\sales;

use common\models\catalog\CatalogProduct;
use yii\db\ActiveRecord;
use common\models\sales\query\SalesQuoteItemQuery;

/**
 * This is the model class for table "sales_quote_item".
 *
 * @property integer $id
 * @property string $quote_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $product_id
 * @property integer $store_id
 * @property integer $parent_item_id
 * @property integer $is_virtual
 * @property int $qty
 * @property int $qty_buy_x_get_y
 * @property string $sku
 * @property string $name
 * @property string $description
 * @property integer $free_shipping
 * @property integer $is_qty_decimal
 * @property integer $coupon_code
 * @property string $weight
 * @property string $price
 * @property string $discount_percent
 * @property string $discount_amount
 * @property string $tax_percent
 * @property string $tax_amount
 * @property string $row_total
 * @property string $row_total_with_discount
 * @property string $row_weight
 * @property string $product_type
 * @property string $tax_before_discount
 * @property string $original_custom_price
 * @property string $redirect_url
 * @property string $cost
 * @property string $price_incl_tax
 * @property string $row_total_incl_tax
 *
 * @property CatalogProduct $product
**/

class SalesQuoteItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_quote_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','product_id', 'store_id', 'parent_item_id', 'is_virtual', 'free_shipping', 'is_qty_decimal', 'coupon_code', 'qty_buy_x_get_y'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['weight', 'qty', 'price', 'discount_percent', 'discount_amount', 'tax_percent', 'tax_amount', 'row_total', 'row_total_with_discount', 'row_weight', 'tax_before_discount', 'original_custom_price', 'cost', 'price_incl_tax', 'row_total_incl_tax'], 'number'],
            [['quote_id','sku', 'name', 'product_type', 'redirect_url'], 'string', 'max' => 255],
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
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'parent_item_id' => 'Parent Item ID',
            'is_virtual' => 'Is Virtual',
            'qty' => 'Qty',
            'qty_buy_x_get_y' => 'Qty Buy X Get Y',
            'sku' => 'Sku',
            'name' => 'Name',
            'description' => 'Description',
            'free_shipping' => 'Free Shipping',
            'is_qty_decimal' => 'Is Qty Decimal',
            'coupon_code' => 'Coupon Code',
            'weight' => 'Weight',
            'price' => 'Price',
            'discount_percent' => 'Discount Percent',
            'discount_amount' => 'Discount Amount',
            'tax_percent' => 'Tax Percent',
            'tax_amount' => 'Tax Amount',
            'row_total' => 'Row Total',
            'row_total_with_discount' => 'Row Total With Discount',
            'row_weight' => 'Row Weight',
            'product_type' => 'Product Type',
            'tax_before_discount' => 'Tax Before Discount',
            'original_custom_price' => 'Original Custom Price',
            'redirect_url' => 'Redirect Url',
            'cost' => 'Cost',
            'price_incl_tax' => 'Price Incl Tax',
            'row_total_incl_tax' => 'Row Total Incl Tax',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return SalesQuoteItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesQuoteItemQuery(get_called_class());
    }
}