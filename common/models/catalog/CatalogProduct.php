<?php

namespace common\models\catalog;

use common\models\promotion\PromotionDiscount;
use Yii;
use common\components\wotcart\ItemInterface;
use common\components\wotcart\ItemTrait;
use common\models\catalog\query\CatalogProductQuery;
use common\components\CurrentStore;
use common\models\core\Store;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $id
 * @property string $slug
 * @property integer $parent_id
 * @property integer $store_id
 * @property integer $brand_id
 * @property integer $feature_id
 * @property string $type
 * @property string $legacy_category_ids
 * @property integer $independent
 * @property tinyint $has_options
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $needs_seachanise_update
 *
 *
 * @property CatalogAttributeValue[] $catalogAttributeValues
 * @property CatalogCategoryProduct[] $catalogCategoryProducts
 * @property CatalogProduct $id0
 * @property CatalogProduct $catalogProduct
 * @property CatalogBrand $id1
 * @property CatalogProductFeature $id2
 * @property CatalogBrand[] $ids
 * @property CatalogProductFeature[] $ids0
 * @property CatalogProductAttributeSet[] $catalogProductAttributeSets
 * @property CatalogStoreProduct[] $catalogStoreProducts
 */
class CatalogProduct extends \yii\db\ActiveRecord implements ItemInterface
{
    use ItemTrait;

    const GROUPED = 'grouped';
    const CONFIGURABLE = 'configurable';
    const CHILD_SIMPLE = 'child-simple';
    const SIMPLE = 'simple';

    public $selected_features;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'store_id', 'brand_id', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'needs_seachanise_update'], 'integer'],
            [['feature_id'], 'safe'],
            [['type', 'slug', 'legacy_category_ids'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['selected_features'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'store_id' => 'Store ID',
            'brand_id' => 'Brand ID',
            'feature_id' => 'Feature ID',
            'type' => 'Type',
            'has_options' => 'Has Options',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'needs_seachanise_update' => "Needs Seachanise Update"
        ];
    }

    /**
     * Get Price of current Cart Item
     *  > gets starting price based on special prices and child products
     *
     * @param null $starting_at
     * @param null $strike (if !null, will return array with keys `price` and `strike`)
     * @return array|float
     */
    public function getTierPrice($product_id, $qty)
    {
        $price = [];
        $tier = CatalogProductTierPrice::find()->where(['product_id' => $product_id, 'qty' => $qty])->one();

        $price['price'] = 0.00;
        if (isset($tier)) {
            $price['price'] = floatval($tier->value);
        }


        return $price;
    }

    /**
     * Get Price of current Cart Item
     *  > gets starting price based on special prices and child products
     *
     * @param null $starting_at
     * @param null $strike (if !null, will return array with keys `price` and `strike`)
     * @return array|float
     */
    public function getPrice($starting_at = null, $strike = null)
    {
        $price = 0;
        $discount = 0;
        $strike_price = null;
        $price_a = self::getOriginalPrice($this->id);
        $price_b = self::getSpecialPrice($this->id);

        if ($starting_at) {
            if ($this && !isset($this->parent_id)) {
                $children = CatalogProduct::find()
                    ->where([
                        'parent_id' => $this->id,
                        'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]
                    ])
                    ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                        ->select('product_id')
                        ->where(['store_id' => CurrentStore::getStoreId()])
                        ->andWhere([
                            'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                            'value' => '1'
                        ])
                    ])->all();

                if ($children) {
                    foreach ($children as $child) {
                        $originalPrice = self::getOriginalPrice($child->id);

                        if (isset($originalPrice) && $originalPrice > 0)
                            $originalPrices[] = $originalPrice;

                        $specialPrice = self::getSpecialPrice($child->id);
                        if (isset($specialPrice))
                            $specialPrices[] = $specialPrice;
                    }

                    if (isset($originalPrices)) {
                        sort($originalPrices, SORT_NUMERIC);
                        $price_a = $originalPrices[0];
                    }

                    if (isset($specialPrices)) {
                        sort($specialPrices, SORT_NUMERIC);
                        $price_b = $specialPrices[0];
                    }
                }
            }


        } else {

            if (isset($price_a) && isset($price_b) && $price_b > 0) {
                if (round(floatval($price_b)) < round(floatval($price_a))) {
                    ($strike) ? $strike_price = $price_a : null;
                    $price = $price_b;
                } else {
                    $price = $price_a;
                }
            } elseif (isset($price_a)) {
                $price = $price_a;
            }
        }


        /**
         * Determine whether this product is affected by a targeted discount
         */
        $targetedDiscount = PromotionDiscount::productHasTargetedDiscount($this->id);
        if ($targetedDiscount) {
            switch ($targetedDiscount->type) {
                case 'percent':
                    $discount = ($price / 100) * $targetedDiscount->amount;
                    break;
                case 'fixed':
                    $discount = $targetedDiscount->amount;
                    break;
            }
        }

        /**
         * Generate output
         */
        $price = round($price - $discount, 2);
        $output = $price;
        if ($strike) {
            $output = [
                'price' => $price,
                'strike_price' => $strike_price ? round($strike_price, 2) : $strike_price
            ];
        }

        return $output;
    }

    public function getOriginalStartingAtPrice()
    {
        $product = $this;
        $product_id = $this->id;

        if ($product && !isset($product->parent_id)) {
            $current_store_id = CurrentStore::getStoreId();
            $price_a = self::getOriginalPrice($product_id);


            //get attribute
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                SELECT id
                FROM `catalog_attribute`
                WHERE `slug` = 'active'
            ");
            $attribute = $command->queryOne();

            if ($attribute) {
                $attribute_id = $attribute['id'];

                //get children
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT id
                    FROM `catalog_product`
                    WHERE (`parent_id` = $product_id AND `store_id` IN (0, $current_store_id) )
                    AND (`id` IN (SELECT `product_id` 
                                  FROM `catalog_attribute_value` 
                                  WHERE (`store_id`=$current_store_id) 
                                  AND ((`attribute_id`=$attribute_id) 
                                  AND (`value`='1'))))
                ");
                $product_children = $command->queryAll();

                if ($product_children) {
                    foreach ($product_children as $product_child) {
                        $originalPrice = self::getOriginalPrice($product_child['id']);

                        if (isset($originalPrice) && $originalPrice > 0)   //skip if base price is 0
                            $originalPrices[] = $originalPrice;

                    }

                    if (isset($originalPrices)) {
                        sort($originalPrices, SORT_NUMERIC);
                        $price_a = $originalPrices[0];
                    }

                    if (isset($specialPrices)) {
                        sort($specialPrices, SORT_NUMERIC);
                        $price_b = $specialPrices[0];
                    }
                }
            }
        }


        if (isset($price_a) && isset($price_b)) {
            if (round(floatval($price_b)) < round(floatval($price_a))) {
                return number_format(floatval($price_b), 2);
            } else {
                if (floatval($price_a) > 0) {
                    return number_format(floatval($price_a), 2);
                }
            }
        } elseif (isset($price_a) && floatval($price_a) > 0) {
            return number_format((float)$price_a, 2);
        }

        return null;
    }

    public static function getFinalPrice($product_id, $starting_at = null, $strike = null)
    {
        $product = self::findOne($product_id);
        $price = 0;
        $discount = 0;
        $strike_price = null;
        $price_a = self::getOriginalPrice($product->id);
        $price_b = self::getSpecialPrice($product->id);


        if ($starting_at) {
            if ($product && !isset($product->parent_id)) {
                $children = CatalogProduct::find()
                    ->where([
                        'parent_id' => $product->id,
                        'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]
                    ])
                    ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                        ->select('product_id')
                        ->where(['store_id' => CurrentStore::getStoreId()])
                        ->andWhere([
                            'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                            'value' => '1'
                        ])
                    ])->all();

                if ($children) {
                    foreach ($children as $child) {
                        $originalPrice = self::getOriginalPrice($child->id);

                        if (isset($originalPrice) && $originalPrice > 0)
                            $originalPrices[] = $originalPrice;

                        $specialPrice = self::getSpecialPrice($child->id);
                        if (isset($specialPrice))
                            $specialPrices[] = $specialPrice;
                    }

                    if (isset($originalPrices)) {
                        sort($originalPrices, SORT_NUMERIC);
                        $price_a = $originalPrices[0];
                    }

                    if (isset($specialPrices)) {
                        sort($specialPrices, SORT_NUMERIC);
                        $price_b = $specialPrices[0];
                    }
                }
            }

            if (isset($price_a) && isset($price_b)) {
                if (round(floatval($price_b)) < round(floatval($price_a))) {
                    $strike_price = $price_a;
                    $price = $price_b;
                } else {
                    $price = $price_a;
                }
            } elseif (isset($price_a)) {
                $price = $price_a;
            }
        } else {

            if (isset($price_a) && isset($price_b)) {
                if (round(floatval($price_b)) < round(floatval($price_a))) {
                    $price = $price_b;
                } else {
                    $price = $price_a;
                }
            } elseif (isset($price_a)) {
                $price = $price_a;
            }
        }

        /**
         * Determine whether this product is affected by a targeted discount
         */
        $targetedDiscount = PromotionDiscount::productHasTargetedDiscount($product->id);
        if ($targetedDiscount) {
            switch ($targetedDiscount->type) {
                case 'percent':
                    $discount = ($price / 100) * $targetedDiscount->amount;
                    break;
                case 'fixed':
                    $discount = $targetedDiscount->amount;
                    break;
            }
        }

        /**
         * Generate output
         */
        $price = round($price - $discount, 2);
        $output = number_format((float)$price, 2);
        if ($strike) {
            $output = [
                'price' => number_format((float)$price, 2),
                'strike_price' => $strike_price ? round($strike_price, 2) : $strike_price
            ];
        }

        return $output;
    }


    public function getStartingAtPrice()
    {
        $product = $this;
        $product_id = $this->id;

        if ($product && !isset($product->parent_id)) {
            $current_store_id = CurrentStore::getStoreId();
            $price_a = self::getOriginalPrice($product_id);
            $price_b = self::getSpecialPrice($product_id);


            //get attribute
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                SELECT id
                FROM `catalog_attribute`
                WHERE `slug` = 'active'
            ");
            $attribute = $command->queryOne();

            if ($attribute) {
                $attribute_id = $attribute['id'];

                //get children
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT id
                    FROM `catalog_product`
                    WHERE (`parent_id` = $product_id AND `store_id` IN (0, $current_store_id) )
                    AND (`id` IN (SELECT `product_id` 
                                  FROM `catalog_attribute_value` 
                                  WHERE (`store_id`=$current_store_id) 
                                  AND ((`attribute_id`=$attribute_id) 
                                  AND (`value`='1'))))
                ");
                $product_children = $command->queryAll();

                if ($product_children) {
                    foreach ($product_children as $product_child) {
                        $originalPrice = self::getOriginalPrice($product_child['id']);

                        if (isset($originalPrice) && $originalPrice > 0)   //skip if base price is 0
                            $originalPrices[] = $originalPrice;

                        $specialPrice = self::getSpecialPrice($product_child['id']);
                        if (isset($specialPrice))
                            $specialPrices[] = $specialPrice;
                    }

                    if (isset($originalPrices)) {
                        sort($originalPrices, SORT_NUMERIC);
                        $price_a = $originalPrices[0];
                    }

                    if (isset($specialPrices)) {
                        sort($specialPrices, SORT_NUMERIC);
                        $price_b = $specialPrices[0];
                    }
                }
            }
        }


        if (isset($price_a) && isset($price_b)) {
            if (round(floatval($price_b)) < round(floatval($price_a))) {
                return number_format($price_b, 2);
            } else {
                if (floatval($price_a) > 0) {
                    return number_format($price_a, 2);
                }
            }
        } elseif (isset($price_a) && floatval($price_a) > 0) {
            return number_format($price_a, 2);
        }

        return null;
    }


    public function getDisplayPrice()
    {
        $price = $this->getPrice();
        if (is_array($price))
            $price = $price['price'];

        return number_format($price, 2, '.', ',');
    }

    public function getDisplayCost()
    {
        return number_format($this->getCost(), 2, '.', ',');
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    public function getSalesTax()
    {
        //@TODO: dynamic way to get tax rate by location
        //spoof tax rate to 6%
        $tax_rate = 0.06;

        $sales_tax = 0;
        $sales_tax = $this->getCost() * $tax_rate;

        return round($sales_tax, 2);
    }

    /*
     * End Cart Item Interface
     */

    public static function hasOptions($id)
    {
        $catalog_product = CatalogProduct::findOne($id);

        if (isset($catalog_product) && $catalog_product->has_options) {
            return true;
        } else {
            return false;
        }
    }


    public static function getSku($id)
    {
        $currentProduct = CatalogAttributeValue::find()
            ->where([
                'attribute_id' => CatalogAttribute::findOne(['slug' => 'sku'])->id,
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'product_id' => $id
            ])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        if (empty($currentProduct)) {
            return '';
        } else {
            return $currentProduct->value;
        }
    }

    public static function findBySku($sku)
    {
        $product = CatalogAttributeValue::find()->where([
            'attribute_id' => CatalogAttribute::findOne(['slug' => 'sku'])->id,
            'value' => $sku,
            'store_id' => 0
        ])->orderBy(['id' => SORT_DESC])->one();

        //print_r($product); die;
        if ($product) {
            return CatalogProduct::findOne($product->product_id);
        } else {
            return null;
        }

    }

    public static function getAttributeSet($id)
    {
        $attributeProductSet = CatalogProductAttributeSet::find()
            ->where([
                'product_id' => $id,
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'is_active' => true,
                'is_deleted' => false
            ])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        if (empty($attributeProductSet)) {
            return '';
        } else {
            return $attributeProductSet->set->label;
        }
    }

    public static function getFeatureIdString($pid)
    {
        $features = CatalogProductFeature::findAll(['product_id' => $pid]);

        if ($features)
            return '"' . implode(', ', ArrayHelper::getColumn($features, 'id')) . '"';

        return '';
    }

    public function getProduct($id)
    {
        return CatalogProduct::find()->where(['id' => $id])->one();
    }

    public static function getName($id, $store_id = false)
    {
        if (!$store_id) {
            $store_id = CurrentStore::getStoreId();
        }
        $currentProduct = CatalogAttributeValue::find()
            ->where([
                'attribute_id' => CatalogAttribute::findOne(['slug' => 'name'])->id,
                'store_id' => [Store::NO_STORE, $store_id],
                'product_id' => $id,
            ])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        if (empty($currentProduct)) {
            return null;
        } else {
            return $currentProduct->value;
        }
    }

    public static function getProductCustomOptions($id, $sku)
    {
        $optionValues = [];
        if (CatalogProduct::hasOptions($id)) {
            $options = CatalogProductOption::getOptions($id, CurrentStore::getStoreId());
            foreach ($options as $option) {
                $optionValues[] = CatalogProductOption::getOptionValues($option->option_id);
            }
        }

        $skuExplode = array_reverse(explode(Yii::$app->params['options-sku-delimiter'], $sku));
        $options = [];
        foreach ($skuExplode as $key => $sku) {
            foreach ($optionValues as $optionValue) {
                foreach ($optionValue as $singleOption) {
                    if ($singleOption->sku == $sku) {
                        $options[] = $singleOption->title;
                    }
                }
            }
        }
        return array_reverse(array_unique($options));
    }

    public static function getCustomOptionsPrices($id, $sku)
    {
        $optionValues = [];
        if (CatalogProduct::hasOptions($id)) {
            $options = CatalogProductOption::getOptions($id, CurrentStore::getStoreId());
            foreach ($options as $option) {
                $optionValues[] = CatalogProductOption::getOptionValues($option->option_id);
            }
        }

        $skuExplode = array_reverse(explode(Yii::$app->params['options-sku-delimiter'], $sku));
        $price = 0;
        foreach ($skuExplode as $key => $sku) {
            foreach ($optionValues as $optionValue) {
                foreach ($optionValue as $singleOption) {
                    if ($singleOption->sku == $sku) {
                        $price += $singleOption->price;
                    }
                }
            }
        }

        return $price;
    }


    public static function getCategoriesString($product_id, $slug = false)
    {
        $category_category_product = CatalogCategoryProduct::findAll([
            'product_id' => $product_id
        ]);

        foreach ($category_category_product as $cat_prods) {
            //print_r($category_category_product); die;
            if (isset($cat_prods)) {
                $category = CatalogCategory::findOne($cat_prods->category_id);
                $categories[] = $category->slug;
//                if( $category ){
//                    if( $slug ){
//                        return $category->slug;
//                    }else{
//                        return $category->name;
//                    }
//                }
            }

        }
        return implode(',', $categories);
        return null;
    }

    public static function getCategory($product_id, $slug = false)
    {
        $category_category_product = CatalogCategoryProduct::findOne([
            'product_id' => $product_id
        ]);
        //print_r($category_category_product); die;
        if (isset($category_category_product)) {
            $category = CatalogCategory::findOne($category_category_product->category_id);

            if ($category) {
                if ($slug) {
                    return $category->slug;
                } else {
                    return $category->name;
                }
            }
        }

        return null;
    }

    public static function getCategoryString($product_id, $slug = false)
    {
        $category_category_product = CatalogCategoryProduct::findOne([
            'product_id' => $product_id
        ]);

        if (isset($category_category_product)) {
            $category = CatalogCategory::findOne($category_category_product->category_id);

            if ($category) {
                if ($slug) {
                    return $category->slug;
                } else {
                    return $category->name;
                }
            }
        }

        return null;
    }

    public static function getWebOnly($product_id)
    {
        return self::getAttributeValue($product_id, 'web-only') ? true : false;
    }

    public static function getParentName($id)
    {
        switch (self::findOne($id)->parent_id) {
            case null:
                return null;
                break;

            default:
                $parentProduct = CatalogAttributeValue::find()
                    ->where([
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'name'])->id,
                        'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                        'product_id' => self::findOne($id)->parent_id,
                    ])
                    ->orderBy(['store_id' => SORT_DESC])
                    ->one();

                if (empty($parentProduct)) {
                    return null;
                } else {
                    return $parentProduct->value;
                }
                break;
        }
    }

    public static function getOriginalPrice($product_id)
    {
        $attribute_value = CatalogAttributeValue::find()
            ->where([
                'attribute_id' => CatalogAttribute::findOne(['slug' => 'price'])->id,
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'product_id' => $product_id
            ])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        if ($attribute_value) {
            return $attribute_value['value'];
        }


        return null;
    }

    public static function getSpecialPrice($product_id)
    {
        $current_store_id = CurrentStore::getStoreId();

        //get attribute
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT id, slug
            FROM `catalog_attribute`
            WHERE `slug` IN ('special-price-starts', 'special-price-ends', 'special-price')
        ");
        $attributes = ArrayHelper::map($command->queryAll(), 'slug', 'id');

        //get attribute values
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT *
            FROM `catalog_attribute_value`
            WHERE `product_id` = $product_id
            AND `store_id` IN (0, $current_store_id)
            AND `attribute_id` IN (" . implode(',', $attributes) . ") order by store_id desc;
        ");
        $attribute_values = $command->queryAll();


        $attribute_value = CatalogAttributeValue::find()
            ->where([
                'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price'])->id,
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'product_id' => $product_id
            ])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        //map attribute_values
        $starts_at = null;
        $ends_at = null;
        $special_price = $attribute_value['value'];
        foreach ($attribute_values as $attribute_value) {
            if (array_key_exists('special-price-starts', $attributes)
                && $attribute_value['attribute_id'] == $attributes['special-price-starts']) {
                $starts_at = $attribute_value['value'];
            }

            if (array_key_exists('special-price-ends', $attributes)
                && $attribute_value['attribute_id'] == $attributes['special-price-ends']) {
                $ends_at = $attribute_value['value'];
            }

            if (array_key_exists('special-price', $attributes)
                && $attribute_value['attribute_id'] == $attributes['special-price']) {
                $special_price = $attribute_value['value'];
            }
        }

        if ($special_price) {
            //determine special_price time validity
            $now = time();
            if ($starts_at
                && !$ends_at) {
                $starts_at = strtotime($starts_at);
                if ($now >= $starts_at) {
                    return $special_price;
                }
            }

            if (!$starts_at
                && $ends_at) {
                $ends_at = strtotime($ends_at);
                if ($now <= $ends_at) {
                    return $special_price;
                }
            }

            if ($starts_at
                && $ends_at) {
                $starts_at = strtotime($starts_at);
                $ends_at = strtotime($ends_at);
                if ($now >= $starts_at
                    && $now <= $ends_at) {
                    return $special_price;
                }
            }

            if (!$starts_at
                && !$ends_at) {
                return $special_price;
            }
        }

        return null;


//        $starts_at = CatalogAttributeValue::findOne([
//            'store_id'     => CurrentStore::getStoreId(),
//            'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price-starts'])->id,
//            'product_id'   => $product_id
//        ]);
//
//        $ends_at = CatalogAttributeValue::findOne([
//            'store_id'     => CurrentStore::getStoreId(),
//            'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price-ends'])->id,
//            'product_id'   => $product_id
//        ]);
//
//        $product = CatalogAttributeValue::find()
//            ->where([
//                'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price'])->id,
//                'store_id'     => CurrentStore::getStoreId(),
//                'product_id'   => $product_id
//            ]);
//
//        if ($starts_at) {
//            $product = $product
//                ->andWhere(['IN', 'product_id', CatalogAttributeValue::find()
//                    ->select('product_id')
//                    ->where([
//                        'store_id' => CurrentStore::getStoreId()
//                    ])
//                    ->andWhere([
//                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price-starts'])->id,
//                        'product_id'   => $product_id
//                    ])
//                    ->andWhere('value >= CURDATE() OR value IS NULL')
//                ]);
//        }
//
//        if ($ends_at) {
//            $product = $product
//                ->andWhere(['IN', 'product_id', CatalogAttributeValue::find()
//                    ->select('product_id')
//                    ->where([
//                        'store_id' => CurrentStore::getStoreId()
//                    ])
//                    ->andWhere([
//                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price-ends'])->id,
//                        'product_id'   => $product_id
//                    ])
//                    ->andWhere('value >= CURDATE() OR value IS NULL')
//                ]);
//        }
//
//        $product = $product->one();
//        return $product && isset($product->value) ? $product->value : null;
    }


    public static function getPriceValue($product_id, $starting_at = false, $qty, $sku = false)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            if ($tier = CatalogProduct::hasTieredPricing($product->id, $qty)) {
                $price = $product->getTierPrice($product->id, $tier);
            } else {
                $price = $product->getPrice($starting_at, true);
            }

            if ($sku) {
                $price['price'] += CatalogProduct::getCustomOptionsPrices($product_id, $sku);
            }

            return $price;
        }
        return null;
    }

    public static function getPriceHtml($product_id, $starting_at = false, $strike = true, $grouped = false)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            $price = $product->getPrice(false, true);
//            /var_dump($price); die;
            if ($strike) {
                if ($price['strike_price']) {
                    $class = ($grouped) ? 'grouped sale-price' : 'sale-price';
                    return "<span class='original-price'><strike>$" . number_format($price['strike_price'], 2) . "</strike></span> <span class='" . $class . "'>$" . number_format($price['price'], 2) . "</span>";
                }
                return "<span class='product-price'>$" . number_format($price['price'], 2) . "</span>";
            }
            return "<span class='product-price'>$" . number_format($price['price'], 2) . "</span>";
        }

        return null;
    }

    /**
     * Get pre-defined markup for displaying Price
     *  > depends on $this->getPrice() for price information
     *
     * @param $product_id
     * @param bool $starting_at
     * @return null|string
     */
    public static function getPriceString($product_id, $starting_at = false)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            $price = $product->getPrice($starting_at, true);
            if ($starting_at) {
                if ($price['strike_price']) {
                    return "<strike>" . number_format($price['strike_price'], 2) . "</strike> <span class='product-price'>$" . number_format($price['price'], 2) . "</span>";
                }
                return "<span class='product-price'>" . number_format($price['price'], 2) . "</span>";
            }
            return number_format($price['price'], 2);
        }

        return null;
    }

    public static function getBrand($product_id)
    {
        $catalog_brand = CatalogBrand::findOne(self::findOne($product_id)->brand_id);
        if ($catalog_brand) {
            return $catalog_brand->name;
        }
        return null;
    }

    public static function getProductType($product_id)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            return $product->type;
        }
        return null;
    }

    public static function getProductSlug($product_id)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            return $product->slug;
        }
        return null;
    }

    public static function getBrandSlug($product_id, $empty = false)
    {
        $catalog_brand = CatalogBrand::findOne(self::findOne($product_id)->brand_id);
        if ($catalog_brand) {
            return $catalog_brand->slug;
        }

        if ($empty)
            return '';

        return null;
    }

    public static function getParentSku($product_id)
    {
        $product = CatalogProduct::findOne($product_id);
        return self::getSku($product->parent_id);
    }

    public static function getAttributeValue($product_id, $slug, $all = false, $store_id = false)
    {
        if ($store_id === false) {
            $store_id = CurrentStore::getStoreId();
        }

        $catalog_attribute = CatalogAttribute::findOne(['slug' => $slug]);

        if ($catalog_attribute) {
            $attribute = CatalogAttributeValue::find()
                ->where([
                    'attribute_id' => $catalog_attribute->id,
                    'store_id' => [Store::NO_STORE, $store_id],
                    'product_id' => $product_id,
                ])
                ->orderBy(['store_id' => SORT_DESC]);

            if ($all) {
                $attributes = $attribute->all();
                foreach ($attributes as $attribute) {
                    $values[] = $attribute->value;
                }
                return $values;
            } else {
                $attribute = $attribute->one();
            }

            if ($attribute) {
                return ($attribute->value);
            }
        }

        return null;
    }

    public static function getAttributeOptionValue($product_id, $slug)
    {
        $value = self::getOptionValue(self::getAttributeValue($product_id, $slug));
        return $value ? $value : '';
    }

    public function findAttribute($slug)
    {
        $found_attribute = null;
        $store_attribute = null;
        $national_attribute = null;

        $catalog_attributes = CatalogAttributeValue::find()->bySlug($slug, $this->id)->all();

        if ($catalog_attributes) {
            foreach ($catalog_attributes as $catalog_attribute) {
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

    public static function getOptionValue($option_id)
    {
        $option = CatalogAttributeOption::findOne($option_id);

        if (empty($option)) {
            return null;
        } else {
            return $option->value;
        }
    }

    public static function getListedSwitch($product_id)
    {
        if (!CurrentStore::isNone()) {
            $listed = CatalogStoreProduct::findOne([
                'store_id' => CurrentStore::getStoreId(),
                'product_id' => $product_id
            ]);

            $listed = (empty($listed) ? '' : 'checked');
            $html = '<label class="switch">';
            $html .= "<input type='checkbox' class='list-product' data-id='$product_id' data-url='" . Url::to(['/product/carry']) . "' $listed>";
            $html .= '<div class="slider round"></div>';
            $html .= '</label>';

            return $html;
        }
    }

    public static function getActiveSwitch($id)
    {
        $active = CatalogAttributeValue::storeValue('active', $id);

        $active = (empty($active) ? '' : 'checked');
        $html = '<label class="switch">';
        $html .= "<input type='checkbox' class='list-product' data-id='$id' data-url='" . Url::to(['/product/active']) . "' $active>";
        $html .= '<div class="slider round"></div>';
        $html .= '</label>';

        return $html;
    }

    public static function getVisibilitySwitch($id)
    {
        $active = CatalogAttributeValue::storeValue('visible', $id);

        $active = (empty($active) ? '' : 'checked');
        $html = '<label class="switch">';
        $html .= "<input type='checkbox' class='list-product' data-id='$id' data-url='" . Url::to(['/product/visible']) . "' $active>";
        $html .= '<div class="slider round"></div>';
        $html .= '</label>';

        return $html;
    }

    public static function getIndependentSwitch($id)
    {
        if (CurrentStore::isNone()) {
            $independent = CatalogProduct::findOne([
                'id' => $id,
                'store_id' => Store::NO_STORE,
                'independent' => true
            ]);
        } else {
            $independent = CatalogStoreProduct::findOne([
                'store_id' => CurrentStore::getStoreId(),
                'product_id' => $id,
                'independent' => true
            ]);
        }

        $independent = (empty($independent) ? '' : 'checked');
        $html = '<label class="switch">';
        $html .= "<input type='checkbox' class='list-product' data-id='$id' data-url='" . Url::to(['/product/independent']) . "' $independent>";
        $html .= '<div class="slider round"></div>';
        $html .= '</label>';

        return $html;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeValues()
    {
        return $this->hasMany(CatalogAttributeValue::className(), ['product_id' => 'id']);
    }

    public function getFilterableAttributes()
    {
        return $this->hasMany(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andWhere([
                'is_filterable' => 1
            ]);
    }

    public static function isChild($id)
    {
        return CatalogProduct::findOne($id)->parent_id ? true : false;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategoryProducts()
    {
        return $this->hasOne(CatalogCategoryProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(CatalogProduct::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId1()
    {
        return $this->hasOne(CatalogBrand::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId2()
    {
        return $this->hasOne(CatalogProductFeature::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(CatalogBrand::className(), ['id' => 'id'])->viaTable('catalog_product', ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds0()
    {
        return $this->hasMany(CatalogProductFeature::className(), ['id' => 'id'])->viaTable('catalog_product', ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProductAttributeSets()
    {
        return $this->hasMany(CatalogProductAttributeSet::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogStoreProducts()
    {
        return $this->hasMany(CatalogStoreProduct::className(), ['product_id' => 'id']);
    }

    public function getProductSet()
    {
        return $this->hasOne(CatalogProductAttributeSet::className(), ['product_id' => 'id']);
    }

    public function getProductSetName()
    {
        return $this->hasOne(CatalogAttributeSet::className(), ['set_id' => 'id'])
            ->via('productSet');
    }

    public function getProductBrand()
    {
        return $this->hasOne(CatalogBrand::className(), ['id' => 'brand_id']);
    }

    public function getProductSku()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andOnCondition(['sku.attribute_id' => CatalogAttribute::findOne(['slug' => 'SKU'])->id]);
    }

    public function getProductName()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andOnCondition(['product_name.attribute_id' => CatalogAttribute::findOne(['slug' => 'name'])->id]);
    }

    public function getProductPrice()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andOnCondition(['price.attribute_id' => CatalogAttribute::findOne(['slug' => 'price'])->id]);
    }

    public function getProductSpecialPrice()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andOnCondition(['special_price.attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price'])->id]);
    }

    public function getProductActive()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['product_id' => 'id'])
            ->andOnCondition(['attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id]);
    }

    public function getRelatedProducts($product_id)
    {
        $type = CatalogProductRelationType::findOne(["type_name" => "related"]);
        $catalog_product = CatalogProductRelation::find()->where(['product_id_1' => $product_id, 'type_id' => $type->id])->all();
        $linked_ids = ArrayHelper::getColumn($catalog_product, 'product_id_2');
        if ($linked_ids) {
            return CatalogProduct::find()->where([
                'id' => $linked_ids,
                //'is_active' => true
            ])->all();
        } else {
            return false;
        }

    }

    public static function deleteProductByBrand($brand_id = false)
    {
        if ($brand_id) {
            $products = CatalogProduct::findAll([
                'brand_id' => $brand_id
            ]);

            if ($products) {
                $product_ids = ArrayHelper::getColumn($products, 'id');

                // Remove all product attribute records
                CatalogAttributeValue::deleteAll([
                    'product_id' => $product_ids
                ]);

                // Remove all product records
                CatalogProduct::deleteAll([
                    'id' => $product_ids
                ]);
            }

            return true;
        }

        return false;
    }

    public function findAttributeValue($attribute_slug)
    {
        $catalog_attribute = CatalogAttribute::find()
            ->select('id')
            ->where([
                'slug' => $attribute_slug
            ])
            ->one();

        if ($catalog_attribute) {
            $catalog_attribute_value = CatalogAttributeValue::find()
                ->where([
                    'product_id' => $this->id,
                    'attribute_id' => $catalog_attribute->id
                ])
                ->orderBy('store_id DESC')
                ->one();

            if ($catalog_attribute_value) {
                if ($catalog_attribute->type == 2) {
                    $catalog_attribute_option = CatalogAttributeOption::findOne($catalog_attribute_value->value);

                    if ($catalog_attribute_option) {
                        return $catalog_attribute_option;
                    }
                }

                return $catalog_attribute_value->value;
            }
        }

        return '';
    }

    public function getSet()
    {
        return $this->hasMany(CatalogAttributeSet::className(), ['id' => 'set_ids'])
            ->viaTable('catalog_product_attribute_set', ['product_ids' => 'ids'])
            ->one();
    }


    /**
     * vie table relationship - this will return the CatalogFeatures for this model (CatalogProduct)
     * access this with $model->features
     *
     * @return $this
     */
    public function getFeatures()
    {
        return $this->hasMany(CatalogFeature::className(), ['id' => 'feature_id'])
            ->viaTable('catalog_product_feature', ['product_id' => 'id']);
    }

    /**
     * Save a collection of $this->selected_features IDs as CatalogFeature->CatalogProduct relationships
     *
     * @return nil
     */
    public function saveFeatures()
    {
        if ($this->selected_features
            || is_array($this->selected_features)
            || count($this->selected_features) > 0) {           //each of these are validated in sequence. if the chain is broken then it fails

            $this->unlinkAll('features', true);                 //remove all existing relationships
            $found_features = CatalogFeature::find()->where([   //find out CatalogFeature models (these are required for ->link())
                'id' => $this->selected_features
            ])->all();
            foreach ($found_features as $feature) {
                $this->link('features', $feature);              //relink each model
            }

        }
    }


    /**
     * Get Reviews for a product based on children and current store
     *
     * @return array
     */
    public function getReviews()
    {
        $current_store_id = CurrentStore::getStoreId();
        $product_ids = [$this->id];

        //get children
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT id
            FROM `catalog_product`
            WHERE `parent_id` = $this->id
            ");
        $child_product_ids = ArrayHelper::getColumn($command->queryAll(), 'id');

        //merge product_ids and child_product_ids
        $product_ids = array_merge($product_ids, $child_product_ids);

        //get reviews for both in order of creation
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT 
              `catalog_product_review`.`title` AS title,
              `catalog_product_review`.`detail` AS detail,
              `catalog_product_review`.`rating` AS rating,
              `catalog_product_review`.`created_at` AS created_at,
              `customer`.`first_name` AS first_name,
              `customer`.`last_name` AS last_name
            FROM `catalog_product_review`
            LEFT JOIN `customer` ON `customer`.`id` = `catalog_product_review`.`customer_id`
            WHERE `product_id` IN (" . implode(',', $product_ids) . ")
            AND `catalog_product_review`.`store_id` = $current_store_id
            AND `approved` = 1
            ORDER BY `catalog_product_review`.`created_at` DESC
            ");
        $reviews = $command->queryAll();

        return $reviews;    //will return the reviews, or an empty array
    }

    public function getAverageReviewRatings()
    {
        $reviews = $this->reviews;


        $ratings = 0;
        $total = count($reviews);

        if ($total > 0) {
            foreach ($reviews as $review) {
                $ratings = $ratings + intval($review['rating']);
            }
            return ceil($ratings / $total);
        }

        return 0;
    }

    public function userHasReviewed()
    {
        $current_user_id = Yii::$app->user ? Yii::$app->user->id : null;
        $current_store_id = CurrentStore::getStoreId();
        $product_id = $this->id;

        if ($current_user_id) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                SELECT *
                FROM `catalog_product_review`
                WHERE `customer_id` = $current_user_id
                AND `store_id` = $current_store_id
                AND `product_id`  = $product_id
            ");
            $review = $command->queryOne();

            return $review ? true : false;
        }

        return false;
    }

    public static function getGroupedChildren($id)
    {
        $products = CatalogProduct::find()->where(['parent_id' => $id]);

        $sort = CatalogAttribute::find()
            ->where(['slug' => "associated-product-sort"])
            ->one();

        $products->leftJoin('catalog_attribute_value sort', 'sort.product_id = catalog_product.id')
            ->onCondition(['sort.store_id' => CurrentStore::getStoreId()])
            ->onCondition(['sort.attribute_id' => $sort->id])
            ->orderBy(['cast(sort.value as unsigned)' => SORT_ASC]);

        $products = $products->all();
        return $products;
    }


    public static function getGalleryImages($product_id, $attribute_id = null)
    {

        if ($attribute_id == 'base-image') {
            $image = CatalogProductGallery::find()
                ->where(['store_id' => 0, 'is_default' => 1, 'product_id' => $product_id])
                ->one();

            return isset($image) ? $image : '';
        } else {
            $images = CatalogProductGallery::find()
                ->where(['store_id' => 0, 'product_id' => $product_id])->orderBy(['sort' => SORT_DESC])
                ->all();
        }

        if (sizeof($images) == 1) {
            return;
        }
        return $images;

    }

    public static function getCategoryBreadcrumbs($category = null)
    {
        if ($category == null) {
            return false;
        }

        if ($category) {
            $parent_category = CatalogCategory::findOne($category->parent_id);
            if ($parent_category) {
                $breadcrumbs[] = [
                    'name' => $parent_category->name,
                    'url' => $parent_category->slug,
                    'parent' => true
                ];
            } else {
                $breadcrumbs[] = [
                    'name' => $category->name,
                    'url' => $category->slug,
                    'parent' => false
                ];


            }
            return $breadcrumbs;
        }

        return null;
    }

    public static function getProductBreadcrumbs($product_id = null)
    {
        if ($product_id == null) {
            throwException();
        }


        $category_category_product = CatalogCategoryProduct::find()->where([
            'product_id' => $product_id
        ])->all();


        if ($category_category_product) {
            $breadcrumbs = [];
            foreach ($category_category_product as $product) {
                if ($category = CatalogCategory::findOne(["id" => $product->category_id])) {
                    $breadcrumbs[] = [
                        'name' => $category->name,
                        'url' => $category->slug,
                    ];
                }
            }
            return $breadcrumbs;

        }

        return null;


    }

    public static function isOnSale($product_id)
    {
        $saleStart = CatalogProduct::getAttributeValue($product_id, 'special-price-starts');
        $saleEnd = CatalogProduct::getAttributeValue($product_id, 'special-price-ends');
        $sale = (CatalogProduct::getAttributeValue($product_id, 'special-price') > 0) ? true : false;

        if ($sale) {
            if (empty($saleStart) || strtotime($saleStart) < time()) {
                if (empty($saleEnd) || strtotime($saleEnd) >= time()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function isSaleBanner($product_id)
    {
        if (CatalogProduct::getProductType($product_id) == CatalogProduct::GROUPED || CatalogProduct::getProductType($product_id) == CatalogProduct::CONFIGURABLE) {
            $children = CatalogProduct::getGroupedChildren($product_id);
            foreach ($children as $child) {
                $sale = self::isOnSale($child->id);
                if ($sale) {
                    return true;
                }
            }
        } else {
            return self::isOnSale($product_id);
        }

        return false;

    }

    public static function isNewBanner($product_id)
    {

        $newStart = CatalogProduct::getAttributeValue($product_id, 'new-banner-starts');
        $newEnd = CatalogProduct::getAttributeValue($product_id, 'new-banner-ends');
        $new = CatalogProduct::getAttributeValue($product_id, 'new');

        if ($new) {
            if (empty($newStart) || strtotime($newStart) < time()) {
                if (empty($newEnd) || strtotime($newEnd) >= time()) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function getTieredPricing($product_id)
    {
        return CatalogProductTierPrice::find()->where(['product_id' => $product_id, 'store_id' => CurrentStore::getStoreId()])->all();
    }

    public static function hasTieredPricing($product_id, $qty)
    {
        $tieredPricing = CatalogProductTierPrice::find()->where(['product_id' => $product_id, 'store_id' => CurrentStore::getStoreId()])->all();
        $tier_levels = [];

        if (isset($tieredPricing)) {

            foreach ($tieredPricing as $tier) {
                $tier_levels[] = $tier->qty;
            }
            $tier_count = sizeof($tier_levels);

            for ($i = 0; $i <= $tier_count - 1; $i++) {
                if ($i < $tier_count - 1) {
                    if ($qty >= $tier_levels[$i] && $qty < $tier_levels[$i + 1]) {
                        return $tier_levels[$i];
                    }
                } elseif ($i == $tier_count - 1) {
                    if ($qty >= $tier_levels[$i]) {
                        return $tier_levels[$i];
                    }
                }
            }
        }
        return false;
    }

    public static function findProductAttributeValues($product_id, $attributes)
    {
        $attribute_ids = ArrayHelper::getColumn($attributes, 'id');

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
              SELECT *
              FROM catalog_attribute_value
              WHERE attribute_id IN (" . implode(', ', $attribute_ids) . ")
              AND store_id IN ('" . Store::NO_STORE . "', '" . CurrentStore::getStoreId() . "')
              AND product_id = '" . $product_id . "'
              ORDER BY store_id DESC
            ");
        return $command->queryAll();
    }

    public static function filterAttributeValuesByKey($key, $attributes, $attribute_values)
    {
        $value = null;
        $found_attribute = null;
        $current_store_id = CurrentStore::getStoreId();
        if (!$current_store_id) {
            $current_store_id = 0;
        }

        foreach ($attributes as $attribute) {
            if ($attribute['slug'] == $key) {
                $found_attribute = $attribute;
                break;
            }
        }

        if ($found_attribute) {
            foreach ($attribute_values as $attribute_value) { //current store pass
                if ($attribute_value['store_id'] == $current_store_id
                    && $attribute_value['attribute_id'] == $found_attribute['id']) {
                    $value = $attribute_value['value'];
                    break;
                }
            }

            if (!isset($value)) {
                foreach ($attribute_values as $attribute_value) { //backup store pass
                    if ($attribute_value['store_id'] == 0
                        && $attribute_value['attribute_id'] == $found_attribute['id']) {
                        $value = $attribute_value['value'];
                        break;
                    }
                }
            }
        }


        if ($value
            && $found_attribute['type_id'] == 2) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                      SELECT value
                      FROM catalog_attribute_option
                      WHERE id = '$value'
                    ");
            $attribute_option = $command->queryAll();

            if (count($attribute_option) > 0) {
                return $attribute_option[0]['value'];
            }
        }

        return $value;
    }

    public static function findBrandByProduct($brand_id)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
          SELECT *
          FROM catalog_brand
          WHERE id = '$brand_id'
          LIMIT 1;
        ");
        $result = $command->queryAll();

        if (count($result) > 0) {
            return $result[0]['name'];
        }
        return null;
    }

    public function getStoreFeaturedProducts()
    {

        $featured = self::find()
            ->where(['parent_id' => null])
            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'featured'])->id,
                    'value' => true
                ])
            ])
            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                    'value' => true
                ])
            ])->orderBy(['rand()' => SORT_DESC])->limit(4)->all();

        return $featured;
    }


    public static function find()
    {
        return new CatalogProductQuery(get_called_class());
    }


}
