<?php

namespace common\models\catalog;

use common\models\catalog\search\CatalogProductSearch;
use Yii;
use common\components\CurrentStore;
use common\models\catalog\query\CatalogStoreProductQuery;

/**
 * This is the model class for table "catalog_store_product".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $product_id
 * @property integer $independent
 * @property integer $is_visible
 * @property integer $created_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogProduct $product
 */
class CatalogStoreProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_store_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'product_id', 'independent', 'is_visible', 'created_at', 'is_active', 'is_deleted'], 'integer'],
            [['product_id', 'created_at'], 'required'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => 'Product ID',
            'independent' => 'Independent',
            'is_visible' => 'Is Visible',
            'created_at' => 'Created At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    public function getCatalogCategoryProducts(){
        return $this->hasOne(CatalogCategoryProduct::className(), ['product_id' => 'id']);
    }


    public function getCatalogAttributeValue(){
        return $this->hasMany(CatalogAttributeValue::className(), [
            'product_id'    => 'product_id',
//            'store_id'      => 'store_id'
        ]);
    }

    public static function isEnabled($product_id, $store_id){
        return self::find()->where([
            'product_id' => $product_id,
            'store_id'   => $store_id,
        ])->exists();
    }
    public static function find()
    {
        return new CatalogStoreProductQuery(get_called_class());
    }

    public function findAttribute($slug){
        $found_attribute    = null;
        $store_attribute    = null;
        $national_attribute = null;

        $catalog_attributes = CatalogAttributeValue::find()->bySlug($slug, $this->product_id)->all();

        if ($catalog_attributes) {
            foreach($catalog_attributes as $catalog_attribute) {
                if ($catalog_attribute->store_id == CurrentStore::getStoreId()) {
                    $store_attribute = $catalog_attribute;
                }

                if ($catalog_attribute->store_id == 0) {
                    $national_attribute = $catalog_attribute;
                }
            }
        }

        if ($store_attribute) {
            $found_attribute = $store_attribute->value;
        } elseif ($national_attribute) {
            $found_attribute = $national_attribute->value;
        }

        return $found_attribute;
    }


    /**
     * @return integer
     */
    public function getPrice(){
        $price        = 0;
        $regularPrice = $this->findAttribute('price');
        $specialPrice = $this->findAttribute('special-price');
        $specialStart = $this->findAttribute('special-price-starts');
        $specialEnd   = $this->findAttribute('special-price-ends');

        if ($regularPrice) {
            $price = $regularPrice;
        }

        if ($specialPrice) {
            if (empty($specialStart) || strtotime($specialStart) < time()) {
                if (empty($specialEnd) || strtotime($specialEnd) >= time()) {
                    $price = $specialPrice;
                }
            }
        }

        // Promo codes are applied in cart getTotal
        //@TODO: discounts
        //@TODO: bundles

        return round($price, 2);
    }
    public function getDisplayPrice(){
        return number_format($this->getPrice(), 2, '.', ',');
    }

    /**
     * @return string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param bool $withDiscount
     * @return integer
     */
    public function getCost($withDiscount = true){
        $cost = $this->getQuantity() * $this->getPrice();
//        $costEvent = new CostCalculationEvent([
//            'baseCost' => $cost,
//        ]);
//        if ($this instanceof Component)
//            $this->trigger(ItemInterface::EVENT_COST_CALCULATION, $costEvent);
//        if ($withDiscount)
//            $cost = max(0, $cost - $costEvent->discountValue);
        return round($cost, 2);
    }
    public function getDisplayCost(){
        return number_format($this->getCost(), 2, '.', ',');
    }

    public function getQuantity(){
        return $this->_quantity;
    }
    public function setQuantity($quantity){
        $this->_quantity = $quantity;
    }

    public function getSalesTax(){
        //@TODO: dynamic way to get tax rate by location
        //spoof tax rate to 6%
        $tax_rate = 0.06;

        $sales_tax = 0;
        $sales_tax = $this->getCost() * $tax_rate;

        return round($sales_tax, 2);
    }

    public static function addProductsToStore($store_id){
        $products = CatalogProduct::find()
            ->where(['is_active' => true, 'is_deleted' => false])
            ->all();

        foreach($products as $product){
            $productStore = new CatalogStoreProduct();
            $productStore->store_id = $store_id;
            $productStore->product_id = $product->id;
            $productStore->save(false);
        }

        return true;
    }
}
