<?php

namespace common\models\sales;

use common\models\catalog\CatalogAttributeValue;
use common\models\core\CoreConfig;
use common\models\promotion\PromotionBuyxgety;
use common\models\promotion\PromotionPromotion;
use common\models\promotion\PromotionStorePromotion;
use yii\db\ActiveRecord;
use common\models\sales\query\SalesQuoteQuery;
use common\components\CurrentStore;
use common\models\catalog\CatalogProductOption;
use common\models\catalog\CatalogProduct;
use common\models\billing\TaxJar;
use common\models\settings\SettingsShipping;
use common\models\promotion\PromotionDiscount;
use Yii;

/**
 * This is the model class for table "sales_quote".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $converted_at
 * @property integer $is_active
 * @property integer $is_virtual
 * @property integer $is_multi_shipping
 * @property integer $quote_count
 * @property string $quote_qty
 * @property integer $orig_order_id
 * @property string $store_to_base_rate
 * @property string $store_to_quote_rate
 * @property string $store_currency_code
 * @property string $quote_currency_code
 * @property string $grand_total
 * @property string $checkout_method
 * @property integer $user_id
 * @property integer $items_qty
 * @property integer $customer_tax_class_id
 * @property integer $customer_group_idl
 * @property string $customer_email
 * @property string $customer_prefix
 * @property string $customer_firstname
 * @property string $customer_middlename
 * @property string $customer_lastname
 * @property string $customer_suffix
 * @property string $customer_note
 * @property integer $customer_note_notify
 * @property integer $customer_is_guest
 * @property string $remote_ip
 * @property string $applied_rule_ids
 * @property string $reserved_order_id
 * @property string $password_hash
 * @property string $coupon_code
 * @property string $global_currency_code
 * @property string $customer_taxvat
 * @property string $customer_gender
 * @property string $subtotal
 * @property string $discount
 * @property string $discount_reward_amount
 * @property string $subtotal_incl_discounts
 * @property integer $is_changed
 * @property integer $trigger_recollect
 * @property string $ext_shipping_info
 * @property integer $is_persistent
 * @property integer $items_count
 */
class SalesQuote extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_quote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_active', 'is_virtual', 'is_multi_shipping', 'items_count', 'orig_order_id', 'user_id', 'customer_tax_class_id', 'customer_group_id', 'customer_note_notify', 'customer_is_guest', 'is_changed', 'trigger_recollect', 'is_persistent'], 'integer'],
            [['created_at', 'updated_at', 'converted_at'], 'safe'],
            [['items_qty', 'store_to_base_rate', 'store_to_quote_rate', 'grand_total', 'subtotal', 'subtotal_incl_discounts', 'discount_reward_amount', 'discount'], 'number'],
            [['ext_shipping_info'], 'string'],
            [['store_currency_code', 'quote_currency_code', 'checkout_method', 'customer_email', 'customer_firstname', 'customer_lastname', 'customer_note', 'applied_rule_ids', 'password_hash', 'coupon_code', 'global_currency_code', 'customer_taxvat', 'customer_gender'], 'string', 'max' => 255],
            [['customer_prefix', 'customer_middlename', 'customer_suffix'], 'string', 'max' => 40],
            [['remote_ip'], 'string', 'max' => 32],
            [['reserved_order_id'], 'string', 'max' => 64],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'converted_at' => 'Converted At',
            'is_active' => 'Is Active',
            'is_virtual' => 'Is Virtual',
            'is_multi_shipping' => 'Is Multi Shipping',
            'items_count' => 'Items Count',
            'items_qty' => 'Items Qty',
            'orig_order_id' => 'Orig Order ID',
            'store_to_base_rate' => 'Store To Base Rate',
            'store_to_quote_rate' => 'Store To Quote Rate',
            'store_currency_code' => 'Store Currency Code',
            'quote_currency_code' => 'Quote Currency Code',
            'grand_total' => 'Grand Total',
            'checkout_method' => 'Checkout Method',
            'user_id' => 'User ID',
            'customer_tax_class_id' => 'Customer Tax Class ID',
            'customer_group_id' => 'Customer Group ID',
            'customer_email' => 'Customer Email',
            'customer_prefix' => 'Customer Prefix',
            'customer_firstname' => 'Customer Firstname',
            'customer_middlename' => 'Customer Middlename',
            'customer_lastname' => 'Customer Lastname',
            'customer_suffix' => 'Customer Suffix',
            'customer_note' => 'Customer Note',
            'customer_note_notify' => 'Customer Note Notify',
            'customer_is_guest' => 'Customer Is Guest',
            'remote_ip' => 'Remote Ip',
            'applied_rule_ids' => 'Applied Rule Ids',
            'reserved_order_id' => 'Reserved Order ID',
            'password_hash' => 'Password Hash',
            'coupon_code' => 'Coupon Code',
            'global_currency_code' => 'Global Currency Code',
            'customer_taxvat' => 'Customer Taxvat',
            'customer_gender' => 'Customer Gender',
            'subtotal' => 'Subtotal',
            'subtotal_incl_discounts' => 'Subtotal With Discount',
            'is_changed' => 'Is Changed',
            'trigger_recollect' => 'Trigger Recollect',
            'ext_shipping_info' => 'Ext Shipping Info',
            'is_persistent' => 'Is Persistent',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesQuoteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesQuoteQuery(get_called_class());
    }

    public static function deleteByID($id)
    {
        if ($id) {
            $salesQuote = SalesQuote::findOne($id);
            if ($salesQuote) {
                $salesQuoteID = $salesQuote->id;
                if ($salesQuote->delete())
                    return salesQuote;
            }
        }
        return null;
    }

    public static function addOrUpdate($item)
    {
        if (self::skuHasAllRequiredOptions($item)) {

            if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
                $salesQuote->items_qty += $item['qty'];
                $salesQuoteItem = SalesQuoteItem::find()->where(['quote_id' => $salesQuote->id])->andWhere(['sku' => $item['sku']])->one();
                $salesQuote->updated_at = time();

                if (!$salesQuoteItem) {
                    $salesQuote->items_count++;
                    $salesQuoteItem = new SalesQuoteItem();
                    $salesQuoteItem->sku = $item['sku'];
                    $salesQuoteItem->price = $item['price']['price'];
                    $salesQuoteItem->product_id = $item['product_id'];
                    $salesQuoteItem->store_id = $salesQuote->store_id;
                    $salesQuoteItem->quote_id = $salesQuote->id;
                }

                $salesQuoteItem->qty += $item['qty'];

                if (isset($item['buy_x_get_y'])) {
                    if ($item['buy_x_get_y'] === true) {
                        $salesQuoteItem->qty_buy_x_get_y += $item['qty'];
                    }
                }

                $salesQuote->save();
                $salesQuoteItem->save();

                self::BuyXGetY($salesQuoteItem, $salesQuote->id, CurrentStore::getStoreId(), "add");
            }
        }
    }

    public function subOne($item)
    {
        if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
            if ($item['qty'] < $salesQuote->items_qty) {
                $salesQuote->items_qty -= $item['qty'];
                $salesQuoteItem = SalesQuoteItem::find()->where(['quote_id' => $salesQuote->id])->andWhere(['sku' => $item['sku']])->one();
                if ($item['qty'] < $salesQuoteItem->qty) {
                    $salesQuoteItem->qty -= $item['qty'];
                    if (isset($item['buy_x_get_y'])) {
                        if ($item['buy_x_get_y'] === true) {
                            $salesQuoteItem->qty_buy_x_get_y -= $item['qty'];
                        }
                    }
                    $salesQuoteItem->save();
                } else {
                    $salesQuoteItem->delete();
                    $salesQuote->items_count--;
                }
                $salesQuote->updated_at = time();
                $salesQuote->save();
                self::BuyXGetY($salesQuoteItem, $salesQuote->id, CurrentStore::getStoreId(), "sub");
            } else {
                SalesQuote::deleteAll(['id' => $salesQuote->id]);
                SalesQuoteItem::deleteAll(['quote_id' => $salesQuote->id]);
            }
        }
    }

    public static function deleteItem($item)
    {
        if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
            $salesQuoteItem = SalesQuoteItem::find()->where(['quote_id' => $salesQuote->id])->andWhere(['sku' => $item['sku']])->one();
            if ($salesQuoteItem) {
                $salesQuote->items_qty -= $salesQuoteItem->qty;
                $salesQuote->items_count--;
                $salesQuote->updated_at = time();
                $salesQuote->save();
                $salesQuoteItem->delete();
            }
            if ($salesQuote->items_count <= 0) {
                SalesQuote::deleteAll(['id' => $salesQuote->id]);
                SalesQuoteItem::deleteAll(['quote_id' => $salesQuote->id]);
            }
        }
    }


    public static function deleteAllItems()
    {
        if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
            SalesQuoteItem::deleteAll(['quote_id' => $salesQuote->id]);
            $salesQuote->updated_at = time();
            $salesQuote->save();
            self::recalculateCartTotals();
        }
    }

    public static function getItemsQty()
    {
        if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
            return (string)$salesQuote->items_qty;
        }
        return '0';
    }

    public static function getItems()
    {
        if (!$salesQuote = SalesQuote::getCurrentSaleQuote()) {
            return [];
        }
        $quote = [];
        $subTotal = 0;
        $salesQuoteItems = SalesQuoteItem::find()->where(['quote_id' => $salesQuote->id])->all();
        foreach ($salesQuoteItems as $salesQuoteItem) {

            /* $discountPercent = 1;
             if ($discount = PromotionDiscount::productHasTargetedDiscount($salesQuoteItem->product_id)) {
                 if ($discount->type == "percent") {
                     if ($discount->amount == 100) {
                         $discountPercent = 0;
                     } else {
                         $discountPercent = $discount->amount / 100;
                     }
                 }
             }*/

            $product = CatalogProduct::findOne($salesQuoteItem->product_id);
            if ($price = CatalogProduct::getPriceValue($salesQuoteItem->product_id, false, $salesQuoteItem->qty, $salesQuoteItem->sku)) {
                $price = $price['price'];
            }
            $options = CatalogProduct::getProductCustomOptions($salesQuoteItem->product_id, $salesQuoteItem->sku);
            $name = CatalogProduct::getName($salesQuoteItem->product_id);
            $quotePrice = $price * ($salesQuoteItem->qty - $salesQuoteItem->qty_buy_x_get_y);
            $subTotal += $quotePrice;
            $quotePrice = number_format($quotePrice, 2, '.', ',');
            if ($salesQuoteItem && $product) {
                $quote['items'][] = ['id' => $salesQuoteItem->product_id, 'slug' => $product->slug, 'name' => $name, 'itemsPrice' => $quotePrice, 'options' => $options, 'qty' => $salesQuoteItem->qty, 'sku' => $salesQuoteItem->sku];
            }
        }

        $rewardValue = self::getRewardPointsValue();
        $discount = self::getDiscount();
        $subTotalWithDiscount = $subTotal - ($discount + $rewardValue);
        $salesTax = self::getTax($subTotal);
        $shippingPrice = self::getShipping($subTotal);
        $quote['rewardValue'] = number_format($rewardValue, 2, '.', null);
        $quote['totalQty'] = self::getItemsQty();
        $quote['subTotalWithDiscount'] = number_format($subTotalWithDiscount, 2, '.', null);
        $quote['subTotal'] = number_format($subTotal, 2, '.', null);
        $quote['salesTax'] = number_format($salesTax, 2, '.', null);
        $quote['shippingPrice'] = number_format($shippingPrice, 2, '.', null);
        $quote['discount'] = number_format($discount, 2, '.', '');
        $quote['grandTotal'] = number_format(($subTotalWithDiscount + $salesTax + $shippingPrice), 2, '.', null);
        self::setSalesQuoteTotals($quote);
        return $quote;
    }

    public static function getCurrentSaleQuote()
    {
        if (!Yii::$app->user->isGuest) {
            if (!$salesQuote = SalesQuote::find()->where(["user_id" => Yii::$app->user->id, "store_id" => CurrentStore::getStoreId(), "is_active" => "1"])->orderBy("created_at DESC")->one()) {
                $salesQuote = new SalesQuote();
                $salesQuote->id = Yii::$app->getSecurity()->generateRandomString(32);
                $salesQuote->store_id = CurrentStore::getStoreId();
                $salesQuote->user_id = Yii::$app->user->id;
                $salesQuote->created_at = time();
                $salesQuote->save();
                $salesQuote->refresh();

            }
            return $salesQuote;
        }
        return false;
    }

    public static function getCurrentSaleQuoteSubTotal()
    {
        if ($quote = self::getCurrentSaleQuote()) {
            return $quote->subtotal;
        }

    }

    public static function getShipping($totalCost)
    {
        $output = 0;
        $store_id = CurrentStore::getStoreId();

        //if( !$this->store_pickup ){
        if ($store_id) {
            $shipping_settings = SettingsShipping::find()->where([
                'store_id' => $store_id
            ])->one();

            if ($shipping_settings) {
                /*  if ($shipping_settings->flat_rate_fee) {                //set flat fee CPM: not being used on SME
                    $output = $shipping_settings->flat_rate_fee;
                }*/

                if ($tableRate = ShippingTableRate::findAll(['store_id' => CurrentStore::getStoreId()])) { //table rates
                    foreach ($tableRate as $rate) {
                        if ($totalCost >= $rate->price) {
                            $output = $rate->cost;
                            //var_dump($output);die;
                        }
                    }
                }

                if ($shipping_settings->free_shipping) {                //set free shipping
                    if ($shipping_settings->free_shipping_min) {          //set free shipping if subtotal exceeds minimum
                        if ($totalCost >= $shipping_settings->free_shipping_min) {
                            $output = 0;
                        }
                    } else {
                        $output = 0;
                    }
                }
            }
        }
        // }

        return $output;
    }

    public static function getDiscount()
    {
        $sales_quote = self::getCurrentSaleQuote();

        return isset($sales_quote) ? $sales_quote->discount : 0.00;
    }

    public static function setDiscount($discount)
    {
        $sales_quote = self::getCurrentSaleQuote();

        $withRewards = $sales_quote->subtotal - $sales_quote->discount_reward_amount;
        if ($withRewards < $discount) {
            if ($sales_quote->discount_reward_amount > $discount) {
                $sales_quote->discount_reward_amount -= $discount;
            } else {
                $sales_quote->discount_reward_amount = 0;
            }
            self::saveRewardPointsValue($sales_quote->discount_reward_amount);
        }

        $sales_quote->discount = $discount;
        $sales_quote->subtotal_incl_discounts = $sales_quote->subtotal - $discount - $sales_quote->discount_reward_amount;
        $sales_quote->save(false);
    }

    public static function saveRewardPointsValue($pointsValue)
    {
        $sales_quote = self::getCurrentSaleQuote();
        $withDiscount = $sales_quote->subtotal - $sales_quote->discount;
        if ($withDiscount <= $pointsValue) {
            $pointsValue = $withDiscount;
        }
        $sales_quote->discount_reward_amount = number_format($pointsValue, 2, '.', '');
        $sales_quote->save(false);
    }

    public static function getRewardPointsValue()
    {
        $sales_quote = self::getCurrentSaleQuote();
        return isset($sales_quote) ? (float)$sales_quote->discount_reward_amount : 0.00;
    }

    public static function setSalesQuoteTotals($quote)
    {
        $sales_quote = self::getCurrentSaleQuote();
        if (isset($sales_quote)) {
            $sales_quote->subtotal = floatval(str_replace(',', '', $quote['subTotal']));
            $sales_quote->subtotal_incl_discounts = floatval(str_replace(',', '', $quote['subTotalWithDiscount']));
            $sales_quote->grand_total = floatval(str_replace(',', '', $quote['grandTotal']));
            $sales_quote->save(false);
        }
    }

    public static function getTax($total)
    {
        if (CoreConfig::getStoreConfig('payment/tax/enabled')) {
            return TaxJar::calculateTax(CurrentStore::getStoreLocation(), $total);
        }
        return 0.00;
    }

    public static function BuyXGetY($item, $quoteId, $storeId, $operation)
    {
        if ($activePromotions = PromotionStorePromotion::find()->where(['IN', 'store_id', [0, $storeId]])->all()) {
            foreach ($activePromotions as $activePromotion) {
                if ($promotion = PromotionPromotion::findOne(["id" => $activePromotion->promotion_id])) {
                    if (time() > $promotion->starts_at && time() < ($promotion->ends_at + mktime(23, 59, 59))) {
                        if ($buyXGetY = PromotionBuyxgety::findOne(["promotion_id" => $promotion->id])) {
                            if (strpos($item->sku, "-")) {
                                $item->sku = strstr($item->sku, '-', true);
                            }
                            if ($buyXGetY->x_sku == $item->sku) {

                                $yCount = 0;
                                if ($y = SalesQuoteItem::find()->where(["quote_id" => $quoteId, 'sku' => $buyXGetY->y_sku])->one()) {
                                    $yCount = $y->qty_buy_x_get_y;
                                }

                                $xRatio = floor($item->qty / $buyXGetY->x_amount);

                                if ($productY = CatalogProduct::findBySku($buyXGetY->y_sku)) {
                                    $free_item = ['product_id' => $productY->id, 'sku' => $buyXGetY->y_sku, 'qty' => $buyXGetY->y_amount, 'buy_x_get_y' => true, 'price' => ["price" => 0]];
                                    switch ($operation) {
                                        case 'add':
                                            if ($yCount < ($xRatio * $buyXGetY->y_amount)) {
                                                self::addOrUpdate($free_item);
                                            }
                                            break;
                                        case 'sub':
                                            if ($yCount > ($xRatio * $buyXGetY->y_amount)) {
                                                self::subOne($free_item);
                                            }
                                            break;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
    }

    public static function getQuoteItems()
    {
        if ($salesQuote = SalesQuote::getCurrentSaleQuote()) {
            return SalesQuoteItem::find()->where(['quote_id' => $salesQuote->id])->all();
        }
    }


    public static function convertToOrder()
    {
        if ($lastQuote = SalesQuote::find()->where(["user_id" => Yii::$app->user->id, "store_id" => CurrentStore::getStoreId() ? CurrentStore::getStoreId() : 1, "is_active" => "1"])->orderBy("created_at DESC")->one()) {
            $lastQuote->converted_at = time();
            $lastQuote->updated_at = time();
            $lastQuote->is_active = 0;
            return $lastQuote->save();
        }
    }

    public static function recalculateCartTotals()
    {
        if ($lastQuote = SalesQuote::find()->where(["user_id" => Yii::$app->user->id, "store_id" => CurrentStore::getStoreId(), "is_active" => "1"])->orderBy("created_at DESC")->one()) {
            $lastQuote->items_qty = SalesQuoteItem::find()->where(['quote_id' => $lastQuote->id])->sum('qty');
            $lastQuote->items_count = SalesQuoteItem::find()->where(['quote_id' => $lastQuote->id])->count();
            $lastQuote->save();
        }
    }

    public static function skuHasAllRequiredOptions($item)
    {
        $initialCount = 0;
        $optionCount = 0;
        if($product = CatalogProduct::findOne(['id' => $item["product_id"]])) {
            if ($product->parent_id !== 'NULL' && $product->parent_id !== NULL) {
                if ($sku = CatalogAttributeValue::find()->where(["product_id" => $item["product_id"], 'attribute_id' => 4])->andWhere(["IN", 'store_id', [CurrentStore::getStoreId(), '0']])->select("value")->orderBy(["store_id" => SORT_DESC])->one()) {
                    $initialCount = substr_count($sku['value'], Yii::$app->params['options-sku-delimiter']);
                }
                if ($options = CatalogProductOption::find()->where(["product_id" => $item["product_id"], "is_required" => 1])->count()) {
                    $optionCount = (int)$options;
                }
            }
        }
        if ((substr_count($item["sku"], Yii::$app->params['options-sku-delimiter']) - $initialCount) >= $optionCount) {
            return true;
        }
        return false;
    }
}