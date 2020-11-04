<?php

namespace common\models\sales;

use common\components\CurrentStore;
use common\models\sales\query\SalesOrderQuery;
use common\models\core\Store;
use common\models\customer\Customer;
use frontend\controllers\CartController;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use common\models\settings\SettingsStore;
use common\models\core\CoreConfig;
use common\models\catalog\CatalogProduct;

/**
 * This is the model class for table "sales_order".
 *
 * @property integer $id
 * @property string $order_id
 * @property string $status
 * @property string $shipping_description
 * @property integer $is_virtual
 * @property integer $store_id
 * @property string $coupon_code
 * @property string $discount_description
 * @property string $discount_amount
 * @property string $discount_reward_amount
 * @property string $discount_canceled
 * @property string $discount_invoiced
 * @property string $discount_refunded
 * @property string $grand_total
 * @property string $shipping_amount
 * @property string $shipping_canceled
 * @property string $shipping_invoiced
 * @property string $subtotal
 * @property string $subtotal_canceled
 * @property string $subtotal_invoiced
 * @property string $subtotal_refunded
 * @property string $tax_amount
 * @property string $tax_canceled
 * @property string $tax_invoiced
 * @property string $tax_refunded
 * @property string $total_canceled
 * @property string $total_invoiced
 * @property string $total_offline_refunded
 * @property string $total_online_refunded
 * @property string $total_paid
 * @property string $total_qty_ordered
 * @property string $total_refunded
 * @property integer $email_sent
 * @property integer $quote_id
 * @property integer $quote_address_id
 * @property integer $billing_address_id
 * @property integer $shipping_address_id
 * @property string $adjustment_negative
 * @property string $adjustment_positive
 * @property string $payment_authorization_amount
 * @property string $shipping_tax_refunded
 * @property string $shipping_refunded
 * @property string $shipping_tax_amount
 * @property string $shipping_discount_amount
 * @property string $shipping_method
 * @property string $shipping_incl_tax
 * @property integer $can_ship_partially_item
 * @property integer $can_ship_partially
 * @property string $subtotal_incl_tax
 * @property string $subtotal_incl_discounts
 * @property string $total_due
 * @property string $weight
 * @property integer $customer_group_id
 * @property integer $customer_is_guest
 * @property integer $customer_id
 * @property integer $customer_note_notify
 * @property string $customer_email
 * @property string $customer_firstname
 * @property string $customer_lastname
 * @property string $customer_middlename
 * @property string $customer_prefix
 * @property string $customer_suffix
 * @property string $customer_taxvat
 * @property string $hold_before_state
 * @property string $hold_before_status
 * @property string $order_currency_code
 * @property string $remote_ip
 * @property string $store_name
 * @property string $customer_note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $total_item_count
 * @property integer $paypal_ipn_customer_notified
 * @property string $comments
 * @property string $purchase_order
 */
class SalesOrder extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_virtual', 'store_id', 'email_sent', 'quote_id', 'quote_address_id', 'billing_address_id', 'shipping_address_id', 'can_ship_partially_item', 'can_ship_partially', 'customer_group_id', 'customer_is_guest', 'customer_id', 'customer_note_notify', 'created_at', 'updated_at', 'total_item_count', 'paypal_ipn_customer_notified', 'status'], 'integer'],
            [['discount_reward_amount', 'discount_amount', 'discount_canceled', 'discount_invoiced', 'discount_refunded', 'grand_total', 'shipping_amount', 'shipping_canceled', 'shipping_invoiced', 'subtotal', 'subtotal_canceled', 'subtotal_invoiced', 'subtotal_refunded', 'tax_amount', 'tax_canceled', 'tax_invoiced', 'tax_refunded', 'total_canceled', 'total_invoiced', 'total_offline_refunded', 'total_online_refunded', 'total_paid', 'total_qty_ordered', 'total_refunded', 'adjustment_negative', 'adjustment_positive', 'payment_authorization_amount', 'shipping_tax_refunded', 'shipping_refunded', 'shipping_tax_amount', 'shipping_discount_amount', 'shipping_incl_tax', 'subtotal_incl_tax', 'subtotal_incl_discounts', 'total_due', 'weight'], 'number'],
            [['customer_note'], 'string'],
            [['order_id'], 'string', 'max' => 50],
            [['shipping_description', 'coupon_code', 'discount_description', 'shipping_method', 'customer_email', 'customer_firstname', 'customer_lastname', 'customer_middlename', 'customer_prefix', 'customer_suffix', 'customer_taxvat', 'hold_before_state', 'hold_before_status', 'order_currency_code', 'remote_ip', 'store_name', 'purchase_order'], 'string', 'max' => 255],
            [['billingAddress', 'shippingAddress'], 'safe']
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
            'status' => 'Status',
            'shipping_description' => 'Shipping Description',
            'is_virtual' => 'Is Virtual',
            'store_id' => 'Store ID',
            'coupon_code' => 'Coupon Code',
            'discount_description' => 'Discount Description',
            'discount_reward_amount' => 'Discount Reward Amount',
            'discount_amount' => 'Discount Amount',
            'discount_canceled' => 'Discount Canceled',
            'discount_invoiced' => 'Discount Invoiced',
            'discount_refunded' => 'Discount Refunded',
            'grand_total' => 'Grand Total',
            'shipping_amount' => 'Shipping Amount',
            'shipping_canceled' => 'Shipping Canceled',
            'shipping_invoiced' => 'Shipping Invoiced',
            'subtotal' => 'Subtotal',
            'subtotal_canceled' => 'Subtotal Canceled',
            'subtotal_invoiced' => 'Subtotal Invoiced',
            'subtotal_refunded' => 'Subtotal Refunded',
            'tax_amount' => 'Tax Amount',
            'tax_canceled' => 'Tax Canceled',
            'tax_invoiced' => 'Tax Invoiced',
            'tax_refunded' => 'Tax Refunded',
            'total_canceled' => 'Total Canceled',
            'total_invoiced' => 'Total Invoiced',
            'total_offline_refunded' => 'Total Offline Refunded',
            'total_online_refunded' => 'Total Online Refunded',
            'total_paid' => 'Total Paid',
            'total_qty_ordered' => 'Total Qty Ordered',
            'total_refunded' => 'Total Refunded',
            'email_sent' => 'Email Sent',
            'quote_id' => 'Quote ID',
            'quote_address_id' => 'Quote Address ID',
            'billing_address_id' => 'Billing Address ID',
            'shipping_address_id' => 'Shipping Address ID',
            'adjustment_negative' => 'Adjustment Negative',
            'adjustment_positive' => 'Adjustment Positive',
            'payment_authorization_amount' => 'Payment Authorization Amount',
            'shipping_tax_refunded' => 'Shipping Tax Refunded',
            'shipping_refunded' => 'Shipping Refunded',
            'shipping_tax_amount' => 'Shipping Tax Amount',
            'shipping_discount_amount' => 'Shipping Discount Amount',
            'shipping_method' => 'Shipping Method',
            'shipping_incl_tax' => 'Shipping Incl Tax',
            'can_ship_partially_item' => 'Can Ship Partially Item',
            'can_ship_partially' => 'Can Ship Partially',
            'subtotal_incl_tax' => 'Subtotal Incl Tax',
            'subtotal_incl_discount' => 'Subtotal Incl Discount',
            'total_due' => 'Total Due',
            'weight' => 'Weight',
            'customer_group_id' => 'Customer Group ID',
            'customer_is_guest' => 'Customer Is Guest',
            'customer_id' => 'Customer ID',
            'customer_note_notify' => 'Customer Note Notify',
            'customer_email' => 'Customer Email',
            'customer_firstname' => 'Customer Firstname',
            'customer_lastname' => 'Customer Lastname',
            'customer_middlename' => 'Customer Middlename',
            'customer_prefix' => 'Customer Prefix',
            'customer_suffix' => 'Customer Suffix',
            'customer_taxvat' => 'Customer Taxvat',
            'hold_before_state' => 'Hold Before State',
            'hold_before_status' => 'Hold Before Status',
            'order_currency_code' => 'Order Currency Code',
            'remote_ip' => 'Remote Ip',
            'store_name' => 'Store Name',
            'customer_note' => 'Customer Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'total_item_count' => 'Total Item Count',
            'paypal_ipn_customer_notified' => 'Paypal Ipn Customer Notified',
        ];
    }


    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {

        $query = new SalesOrderQuery(get_called_class());

        if (!$overRideScope)
            $query->store();

        return $query;
    }

    public static function saveOrderId($order)
    {
        $latest_sales_order = Store::find()
            ->where([
                'is_default' => true
            ])
            ->one();

        if ($latest_sales_order) {
            $order_id = intval($latest_sales_order->last_order_id) + 1;
            $latest_sales_order->last_order_id = $order_id;
            $latest_sales_order->save(false);
        }


        $order->order_id = CurrentStore::getStoreId() . "-" . (string)$order_id;
        if ($order->save(false)) {
            return true;
        }
        return false;
    }

    public function getBillingAddress()
    {
        return $this->hasOne(SalesOrderAddress::className(), ['id' => 'billing_address_id']);
    }

    public function getShippingAddress()
    {
        return $this->hasOne(SalesOrderAddress::className(), ['id' => 'shipping_address_id']);
    }

    public function getOrderStatus()
    {
        return $this->hasOne(SalesOrderStatus::className(), ['order_status_id' => 'status']);
    }

    public function getPayment()
    {
        return $this->hasOne(SalesOrderPayment::className(), ['order_id' => 'id']);
    }

    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    public function getItems()
    {
        return $this->hasMany(SalesOrderItem::className(), ['order_id' => 'id'])->orderBy(['price' => SORT_DESC]);;
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public static function calculateSubtotal()
    {
        $items = SalesQuote::getQuoteItems();
        $subTotal = 0;
        foreach ($items as $item) {
            $subTotal += ($item->price * $item->qty);
        }

        return $subTotal;
    }

    public static function calculateLowestPriceSubtotal()
    {
        $items = SalesQuote::getQuoteItems();
        $subTotal = 0;
        foreach ($items as $item) {
            if ($price = CatalogProduct::getPriceValue($item->product_id, false, $item->qty, $item->sku)) {
                $subTotal += ($price["price"] * $item->qty);
            };
        }
        return $subTotal;
    }

    public static function calculateTaxAmount($subTotal)
    {
        return SalesQuote::getTax($subTotal);
    }

    public static function calculateSubtotalInclTax($subTotal)
    {
        return ($subTotal + self::calculateTaxAmount($subTotal));
    }

    public static function calculateTotal($subTotal)
    {
        return ($subTotal + self::calculateTaxAmount($subTotal) + self::calculateShippingAmount($subTotal));
    }

    public static function recalculateSubtotal($id)
    {
        $items = SalesOrderItem::getOrderItems($id);
        $subTotal = 0;
        foreach ($items as $item) {
            $subTotal += ($item->price * $item->qty_ordered);
        }
        return $subTotal;
    }

    public static function recalculateTaxAmount($subTotal)
    {
        return SalesOrder::getTax($subTotal);
    }

    public static function recalculateSubtotalInclTax($subTotal)
    {
        return ($subTotal + self::recalculateTaxAmount($subTotal));
    }

    public static function recalculateTotal($subTotal)
    {
        return ($subTotal + self::recalculateTaxAmount($subTotal) + self::recalculateShippingAmount($subTotal));
    }


    public function calculateTotalPaid()
    {
        return 0;
    }

    public static function calculateShippingAmount($subTotal)
    {
        return 0;
    }

    public function getItemsCount()
    {
        $items = SalesQuote::getQuoteItems();
        $count = 0;
        foreach ($items as $item) {
            $count = +$item->qty;
        }

        return $count;
    }


    public function getDisplayShippingMethod()
    {
        $output = '';
        switch ($this->shipping_method) {
            case 'store_delivery':
                $output = "Store Delivery";
                break;
            case 'store_pickup':
                $output = "Store Pickup";
                break;
        }
        return $output;
    }

    public static function getTax($total)
    {
//        if (CoreConfig::getStoreConfig('payment/tax/enabled')) {
//            return TaxJar::calculateTax(CurrentStore::getStoreLocation(), $total);
//        }
        return 0.00;
    }

    public static function recalculateShippingAmount($subTotal)
    {
        return 0;
    }

    public static function recalculate($id)
    {
        $order = SalesOrder::find()->where(['id' => $id])->one();
        $subTotal = SalesOrder::recalculateSubtotal($id);

        $order->subtotal = $subTotal;


        $discoutValue = 0;
        if (CartController::getPromoDiscount()) {
            $order->coupon_code = CartController::getPromoCode();
            $discoutValue = CartController::getPromoDiscount();
            $order->discount_amount = $discoutValue;
        }

        $rewardValue = SalesQuote::getRewardPointsValue();

        $discounts = $rewardValue + $discoutValue;
        if ($discounts > $subTotal) {
            $discounts = $subTotal;
        }
        $order->discount_reward_amount = $rewardValue;
        $subTotal -= $discounts;
        $order->subtotal_incl_discounts = $subTotal;
        $order->subtotal_incl_tax = SalesOrder::recalculateSubtotalInclTax($subTotal);
        $order->tax_amount = SalesOrder::recalculateTaxAmount($subTotal);
        $order->grand_total = SalesOrder::recalculateTotal($subTotal);
        $order->total_paid = $order->grand_total;
        $order->updated_at = time();

        //print_r($order); die;

        $order->save(false);
    }

    /**
     * @return string
     */
    public static function checkOrder($id)
    {
        $order = SalesOrder::findOne($id);
        $items = SalesOrderItem::find()->where(['order_id' => $id])->count();
        if ($items > 0) {
            return $order->saveOrderId($order);
        } else {
            $order->delete();
        }

        return false;
    }

    public function getConfirmationRecipients($order_id, $type, $supervised_order = false)
    {
        $to = [];

        if (YII_ENV_DEV) {
            $to[] = ["email" => 'kbrintle+dev@gmail.com'];
            $to[] = ["email" => 'christian+dev@wideopentech.com'];
            return $to;
        }

            if ($type == 'admin') {
                //$to[] = ["email" => 'christian+production@wideopentech.com'];
                //$to[] = ["email" => 'kbrintle+production@wideopentech.com'];
                $to[] = ["email" => 'ordernow@smeincusa.com'];
                $to[] = ["email" => 'kim@smeincusa.com'];
                $to[] = ["email" => 'david@smeincusa.com'];
                $to[] = ["email" => 'jordyn@smeincusa.com'];
            } elseif ($type == 'store') {
                if ($settingsStore = SettingsStore::findOne(['store_id' => CurrentStore::getStoreId()])) {

                    if (isset($settingsStore->sales_email) && strpos($settingsStore->sales_email, ',') !== false) {
                        $storeEmails = explode(',', $settingsStore->sales_email);
                        foreach ($storeEmails as $email) {
                            if (isset($email) && !empty($email)) {
                                $to[] = ["email" => $email];
                            }
                        }
                    } else {
                        $to[] = ["email" => $settingsStore->sales_email];
                    }
                }
            } elseif ($type == 'supervisor') {
                //If this is a supervised order then email the super and not the store sales address
                if ($supervised_order && !empty(CoreConfig::getStoreConfig('general/supervisor/email'))) {
                    $to[] = ["email" => CoreConfig::getStoreConfig('general/supervisor/email')];
                }
            } elseif ($type == 'user') {
                $order = SalesOrder::findOne($order_id);
                $to[] = [
                    "email" => $order->customer_email,
                    "name" => "$order->customer_firstname $order->customer_lastname"
                ];
            }
        return $to;

    }
}
