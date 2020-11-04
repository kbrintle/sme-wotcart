<?php

namespace common\models\sales;

use common\models\catalog\CatalogProduct;
use Yii;
use common\models\sales\query\SalesOrderItemQuery;

/**
 * This is the model class for table "sales_order_item".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $parent_item_id
 * @property integer $quote_item_id
 * @property integer $store_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $product_id
 * @property string $product_type
 * @property string $weight
 * @property string $sku
 * @property string $name
 * @property string $description
 * @property integer $free_shipping
 * @property integer $coupon_code
 * @property string $qty_backordered
 * @property string $qty_canceled
 * @property string $qty_invoiced
 * @property string $qty_ordered
 * @property string $qty_refunded
 * @property string $qty_shipped
 * @property string $cost
 * @property string $price
 * @property string $original_price
 * @property string $subtotal
 * @property string $tax_percent
 * @property string $tax_amount
 * @property string $tax_invoiced
 * @property string $tax_canceled
 * @property string $tax_refunded
 * @property string $tax_before_discount
 * @property string $discount_percent
 * @property string $discount_amount
 * @property string $discount_invoiced
 * @property string $discount_refunded
 * @property string $row_total
 * @property string $row_invoiced
 * @property string $row_weight
 * @property string $row_total_incl_tax
 * @property string $price_incl_tax
 * @property string $total_refunded
 */
class SalesOrderItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'parent_item_id', 'quote_item_id', 'store_id', 'product_id', 'free_shipping', 'coupon_code'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['weight', 'qty_backordered', 'qty_canceled', 'qty_invoiced', 'qty_ordered', 'qty_refunded', 'qty_shipped', 'cost', 'price', 'original_price', 'tax_percent', 'tax_amount', 'tax_invoiced', 'tax_canceled', 'tax_refunded', 'tax_before_discount', 'discount_percent', 'discount_amount', 'discount_invoiced', 'discount_refunded', 'row_total', 'row_invoiced', 'row_weight', 'row_total_incl_tax', 'price_incl_tax', 'total_refunded'], 'number'],
            [['product_type', 'sku', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'parent_item_id' => 'Parent Item ID',
            'quote_item_id' => 'Quote Item ID',
            'store_id' => 'Store ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'product_id' => 'Product ID',
            'product_type' => 'Product Type',
            'weight' => 'Weight',
            'sku' => 'Sku',
            'name' => 'Name',
            'description' => 'Description',
            'free_shipping' => 'Free Shipping',
            'coupon_code' => 'Coupon Code',
            'qty_backordered' => 'Qty Backordered',
            'qty_canceled' => 'Qty Canceled',
            'qty_invoiced' => 'Qty Invoiced',
            'qty_ordered' => 'Qty Ordered',
            'qty_refunded' => 'Qty Refunded',
            'qty_shipped' => 'Qty Shipped',
            'cost' => 'Cost',
            'price' => 'Price',
            'original_price' => 'Original Price',
            'tax_percent' => 'Tax Percent',
            'tax_amount' => 'Tax Amount',
            'tax_invoiced' => 'Tax Invoiced',
            'tax_canceled' => 'Tax Canceled',
            'tax_refunded' => 'Tax Refunded',
            'tax_before_discount' => 'Tax Before Discount',
            'discount_percent' => 'Discount Percent',
            'discount_amount' => 'Discount Amount',
            'discount_invoiced' => 'Discount Invoiced',
            'discount_refunded' => 'Discount Refunded',
            'row_total' => 'Row Total',
            'row_invoiced' => 'Row Invoiced',
            'row_weight' => 'Row Weight',
            'row_total_incl_tax' => 'Row Total Incl Tax',
            'price_incl_tax' => 'Price Incl Tax',
            'total_refunded' => 'Total Refunded',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesOrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesOrderItemQuery(get_called_class());
    }

    public function getCatalogProduct(){
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    public static function getOrderItems($order_id){
        $items = SalesOrderItem::find()->where(['order_id'=>$order_id])->all();
        return $items;
    }
    public static function recalculate($id, $qty){
        $item              = SalesOrderItem::find()->where(['id'=>$id])->one();
        if($qty < 1){
            $item->delete();
        }else{
            $item->qty_ordered = $qty;
            $item->subtotal    = $qty * $item->price;
            $item->row_total   = $item->subtotal;
            $item->price_incl_tax = $item->subtotal + $item->tax_amount;
            $item->updated_at = time();
            $item->save(false);
        }

    }
}