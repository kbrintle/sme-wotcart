<?php

namespace frontend\models;

use app\components\StoreUrl;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogProductGallery;
use frontend\components\Assets;
use Yii;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogProduct;
use common\models\core\Store;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Login form
 */
class StoreFilters extends Model
{
    public $selected_filters = [];
    public $reset_filters = false;

    public $selected_categories = [];

    public $selected_brands = [];
    public $filter_brand; //set this if you want to always post-filter by a brand

    public $sort_type;
    public $sort_order = 'ASC';
    public $page = 0;

    public $price_ranges = [
        [
            'label' => '$0.00 - $299.99',
            'min' => 0,
            'max' => 299.99
        ],
        [
            'label' => '$300.00 - $499.99',
            'min' => 300,
            'max' => 499.99
        ],
        [
            'label' => '$500.00 and above',
            'min' => 500
        ]
    ];
    public $sort_options = [
        'name' => 'Name',
        'price' => 'Price'
    ];

    private $per_page = 12;
    private $_current_store_id;
    private $_products = [];


    public function init()
    {
        $this->_current_store_id = CurrentStore::getStoreId();   //current session store
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['selected_categories', 'selected_brands', 'selected_filters'], 'safe'],
            [['page'], 'integer'],
            [['reset_filters'], 'boolean'],
            [['sort_type', 'sort_order'], 'string']
        ];
        return $rules;
    }

    public function getCategories()
    {
        $category_ids = ArrayHelper::getColumn($this->products, 'category_id');

        $categories = CatalogCategory::find()
            ->where([
                'id' => $category_ids
            ])->all();

        return ArrayHelper::map($categories, 'id', 'name');
    }

    public function getBrands()
    {
        $brand_ids = ArrayHelper::getColumn($this->products, 'brand_id');

        $brands = CatalogBrand::find()
            ->where([
                'id' => $brand_ids
            ])->all();

        return ArrayHelper::map($brands, 'id', 'name');
    }

    public function getFilters()
    {
        $output = [];
        $catalog_attributes = CatalogAttribute::find()
            ->where([
                'is_filterable' => true,
                'is_active' => true
            ])
            ->all();

        foreach ($catalog_attributes as $catalog_attribute) {
            $values = [];

            if ($catalog_attribute->slug == 'price') {
                foreach ($this->products as $product) {
                    array_push($values, $product->startingAtPrice);
                }

                $values = $this->createPriceRanges($catalog_attribute->slug, $values);
            } else {
                foreach ($this->products as $product) {
                    $found_value = $this->findProductAttributeValue($catalog_attribute, $product->id);
                    if ($found_value) {
                        if (array_key_exists('attribute_option', $found_value)) {
                            $values[$found_value['attribute_value']->value] = $found_value['attribute_option']->value;
                        } else {
                            $values[$found_value['attribute_value']->value] = $found_value['attribute_value']->value;
                        }
                    }
                }

                $values = array_unique($values);
            }

            asort($values);

            $output[$catalog_attribute->slug] = [
                'label' => $catalog_attribute->label,
                'values' => $values
            ];
        }

        return $output;
    }

    private function findProductAttributeValue($attribute, $product_id)
    {
        $attribute_value = CatalogAttributeValue::find()
            ->where([
                'attribute_id' => $attribute->id,
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'product_id' => $product_id
            ])
            ->orderBy([
                'store_id' => SORT_DESC
            ])
            ->one();

        if ($attribute_value) {
            if ($attribute->type_id == 2) {
                $attribute_option = CatalogAttributeOption::findOne($attribute_value->value);
                if ($attribute_option) {
                    return [
                        'attribute_option' => $attribute_option,
                        'attribute_value' => $attribute_value
                    ];
                }
            } else {
                return [
                    'attribute_value' => $attribute_value
                ];
            }
        }
        return null;
    }

    private function createPriceRanges($slug, $values)
    {
        if ($slug == 'price') {
            $output = [];

            foreach ($this->price_ranges as $k => $price_range) {

                if (array_key_exists('min', $price_range)
                    && array_key_exists('max', $price_range)) {         //min-max range
                    foreach ($values as $value) {
                        if ($value >= $price_range['min']
                            && $value <= $price_range['max']) {
                            $output[$k] = $price_range['label'];
                            break;
                        }
                    }
                }

                if (array_key_exists('min', $price_range)
                    && !array_key_exists('max', $price_range)) {        //min-∞ range
                    foreach ($values as $value) {
                        if ($value >= $price_range['min']) {
                            $output[$k] = $price_range['label'];
                            break;
                        }
                    }
                }

                if (!array_key_exists('min', $price_range)
                    && array_key_exists('max', $price_range)) {         //0-max range
                    foreach ($values as $value) {
                        if ($value <= $price_range['max']) {
                            $output[$k] = $price_range['label'];
                            break;
                        }
                    }
                }
            }

            return $output;
        }

        return $values;
    }


    public function fieldIsSelected($attribute_slug, $attribute_value)
    {
        if (array_key_exists($attribute_slug, $this->selected_filters)) {
            if (in_array($attribute_value, $this->selected_filters[$attribute_slug])) {
                return true;
            }
        }

        return false;
    }

    private function findFilteredProducts()
    {
        $products = CatalogProduct::find()->storeProducts();

        if (count($this->selected_filters) > 0) {
            foreach ($this->selected_filters as $k => $v) {
                if ($k == 'price') {
                    foreach ($v as $price) {
                        $price_range = $this->price_ranges[intval($price)];
                        $products->filterByStartingPrice($price_range);
                    }
                } else {
                    $products->filterByAttributeValue($k, $v);
                }
            }
        }

        if (count($this->selected_brands) > 0) {
            $products->andWhere([
                'brand_id' => $this->selected_brands
            ]);
        }

        return $products;
    }

    public function findAllProducts()
    {
        $catalog_attribute_id = 33;                           //catalog attribute id that corresponds to `active`
        $catalog_attribute_value = true;                         //catalog attribute boolean for `active`
        $ignore_set = 5;                            //set to ignore when querying products (boxsprings)

        //get Brand ids
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `brand_id`
            FROM `catalog_brand_store`
            WHERE `store_id` = " . $this->_current_store_id);
        $brand_ids = ArrayHelper::getColumn($command->queryAll(), 'brand_id');

        //get Catalog Attribute Value ids
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_attribute_value`
            WHERE (`store_id` = $this->_current_store_id)
            AND (
              (`attribute_id` = $catalog_attribute_id)
              AND (`value` = $catalog_attribute_value)
            )");
        $catalog_attribute_value_ids = ArrayHelper::getColumn($command->queryAll(), 'product_id');

        //get Products
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `catalog_product`.*
            FROM `catalog_product`
            JOIN `catalog_product_attribute_set` ON `catalog_product`.`id` = `catalog_product_attribute_set`.`product_id`
            WHERE (`catalog_product`.`parent_id` IS NULL)
            AND (`catalog_product`.`type` IN ('configurable','grouped', 'simple'))
            AND (`catalog_product`.`brand_id` IN (" . implode(',', $brand_ids) . "))
            AND (`catalog_product`.`id` IN (" . implode(',', $catalog_attribute_value_ids) . "))
            AND `catalog_product_attribute_set`.`store_id` = 0
            GROUP BY `catalog_product`.`id`
        ");
        $products = $command->queryAll();

        return $products;
    }

    public function findAllProductsByCategory($category)
    {
        $catalog_attribute_id = CatalogAttribute::getActiveAttribute()->id;    //catalog attribute id that corresponds to `active`
        //catalog attribute boolean for `active`
        //set to ignore when querying products (boxsprings)

        $categoryModel = CatalogCategory::getCategory($category);
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_category_product`
            WHERE `category_id` = " . $categoryModel->id . " ");


        $product_ids = ArrayHelper::getColumn($command->queryAll(), 'product_id');


        //get Brand ids
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_store_product`
            WHERE `store_id` = " . $this->_current_store_id . "
            AND `product_id` IN (" . implode(',', $product_ids) . ")
        
        ");

        $product_ids = ArrayHelper::getColumn($command->queryAll(), 'product_id');

        //get Products
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `catalog_product`.id
            FROM `catalog_product`
            JOIN `catalog_attribute_value` v ON v.`product_id` = catalog_product.`id`
            WHERE (`catalog_product`.`parent_id` IS NULL)
            AND v.`store_id` = 0
            AND v.`attribute_id` = $catalog_attribute_id
            AND v.`value` = true
            AND (`catalog_product`.`type` NOT IN ('child-simple'))
            AND (`catalog_product`.`id` IN (" . implode(',', $product_ids) . "))
            GROUP BY `catalog_product`.`id`
        ");

        //@todo I saved this query because there was a problem with products saving to their correct attribute set but since they only use 'Default'
        // it didnt matter so i got rid of the join to speed things up - KB
//
//        SELECT `catalog_product`.id
//            FROM `catalog_product`
//            LEFT JOIN `catalog_product_attribute_set` ON `catalog_product`.`id` = `catalog_product_attribute_set`.`product_id`
//            WHERE (`catalog_product`.`parent_id` IS NULL)
//            AND (`catalog_product`.`type` NOT IN ('child-simple'))
//            AND (`catalog_product`.`id` IN (". implode(',', $product_ids) ."))
//            AND `catalog_product_attribute_set`.`store_id` = 0
//            GROUP BY `catalog_product`.`id`

        $product_ids = ArrayHelper::getColumn($command->queryAll(), 'id');


        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `catalog_product`.*
            FROM `catalog_product`
            JOIN `catalog_category_product` ON `catalog_product`.`id` = `catalog_category_product`.`product_id`
            AND `product_id` IN (" . implode(',', $product_ids) . ")
            AND `category_id` = " . $categoryModel->id . "
            ORDER BY COALESCE(sort,999999) asc
        
        ");
        $products = $command->queryAll();
        //print_r($products); die;

        return $products;
    }

    public function findAllProductsByAttribute($attribute)
    {
        $catalog_attribute_id = CatalogAttribute::getActiveAttribute()->id;    //catalog attribute id that corresponds to `active`

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_attribute_value`
            WHERE `attribute_id` = " . $catalog_attribute_id . "
            AND `value` = 1
            AND `store_id` = 0
            ");

        $pids = ArrayHelper::getColumn($command->queryAll(), 'product_id');                                          //catalog attribute boolean for `active`


        if ($attribute == 'new') {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_attribute_value`
            WHERE `attribute_id` = " . CatalogAttribute::getAttributeBySlug($attribute)->id . "
            AND `value` = 1
            AND product_id IN (" . implode(',', $pids) . ")
            ");

            $pids = ArrayHelper::getColumn($command->queryAll(), 'product_id');
            $product_ids = [];
            foreach ($pids as $product_id) {
                if (CatalogProduct::isNewBanner($product_id)) {
                    $product_ids[] = $product_id;
                }
            }

        } else if ($attribute == 'sale') {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                SELECT `product_id`
                FROM `catalog_attribute_value`
                WHERE (`attribute_id` = " . CatalogAttribute::getAttributeBySlug('special-price')->id . "
                AND `value` > 0 )
                 
                AND product_id IN (" . implode(',', $pids) . ")
                ");
            // AND
//                (`attribute_id` = ".  CatalogAttribute::getAttributeBySlug('special-price-starts')->id ."
//                AND `value` >= NOW())
//                AND
//                (`attribute_id` = ".  CatalogAttribute::getAttributeBySlug('special-price-ends')->id ."
//                AND `value` <= NOW())");

            $pids = ArrayHelper::getColumn($command->queryAll(), 'product_id');
            $product_ids = [];
            foreach ($pids as $product_id) {
                if (CatalogProduct::isSaleBanner($product_id)) {
                    $product_ids[] = $product_id;
                }
            }

        }

        //get Brand ids
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `product_id`
            FROM `catalog_store_product`
            WHERE `store_id` = " . $this->_current_store_id . "
            AND `product_id` IN (" . implode(',', $product_ids) . ");
        
        ");
        $product_ids = ArrayHelper::getColumn($command->queryAll(), 'product_id');

        //get Products
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `catalog_product`.*
            FROM `catalog_product`
            WHERE (`catalog_product`.`parent_id` IS NULL)
            AND (`catalog_product`.`type` NOT IN ('child-simple'))
            AND (`catalog_product`.`id` IN (" . implode(',', $product_ids) . "))
            ORDER BY COALESCE(`catalog_product`.`created_at`,999999) DESC
        ");

        $products = $command->queryAll();

        return $products;
    }

    private function setAttributesForProducts()
    {
        //get display attributes
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT *
            FROM `catalog_attribute`
            order by `filter_sort` ASC
            ");
        $attributes = $command->queryAll();

        foreach ($this->_products as $index => $product) {
            $catalog_product = CatalogProduct::findOne($product['id']);
            $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
            $favorite = false;
            $cart = false;
            $temp_arr = [];
            foreach ($attributes as $attribute) {
                if ($attribute['slug'] == 'price') {
                    $original_starting_price = $this->findOriginalStartingPriceForProduct($product['id']);
                    $value = null;
                    $price = str_replace(',', '', $original_starting_price);
                    if ($price) {
                        $value = floatval($price);
                    }
                } elseif ($attribute['slug'] == 'special-price') {
                    $starting_price = $this->findStartingPriceForProduct($product['id']);
                    $value = null;
                    $special_price = str_replace(',', '', $starting_price);
                    if ($special_price) {
                        $value = floatval($special_price);
                    }
                } elseif ($attribute['slug'] == 'sale') {
                    $value = CatalogProduct::isSaleBanner($product['id']);
                } elseif ($attribute['slug'] == 'new') {
                    $value = CatalogProduct::isNewBanner($product['id']);
                } else {
                    $value = CatalogProduct::filterAttributeValuesByKey($attribute['slug'], $attributes, $attribute_values);

                    if ($attribute['slug'] == 'base-image') {
                        $value = $this->findImageForProduct($product['id']);
                    }
                }

                if ($catalog_product->type == CatalogProduct::SIMPLE
                    && $catalog_product->has_options == false
                    && !Yii::$app->user->isGuest) {
                    $favorite = true;
                    $cart = true;
                }

                $temp_arr[$attribute['slug']] = [
                    'label' => $attribute['label'],
                    'value' => is_array($value) ? $value['value'] : $value,
                    'filterable' => $attribute['is_filterable'],
                    'type' => $attribute['type_id'],
                    'order' => is_array($value) ? isset($value['order']) ? $value['order'] : 0 : 0,
                ];

                $temp_arr['on_sale'] = [
                    'label' => 'on_sale',
                    'value' => (CatalogProduct::isSaleBanner($product['id'])) ? 1 : 0,
                    'filterable' => 0,
                    'type' => 0,
                    'order' => 0,
                ];

                $temp_arr['favorite'] = [
                    'label' => 'favorite',
                    'value' => $favorite,
                    'filterable' => 0,
                    'type' => 0,
                    'order' => 0,
                ];

                $temp_arr['cart'] = [
                    'label' => 'cart',
                    'value' => $cart,
                    'filterable' => 0,
                    'type' => 0,
                    'order' => 0,
                ];
            }
            $this->_products[$index]['attributes'] = $temp_arr;

            //set category
            $this->_products[$index]['category'] = $this->findCategoryForProduct($product['id']);

            //set brand
            $this->_products[$index]['brand'] = $this->findBrandForProduct($product['id']);

            //set url
            $this->_products[$index]['url'] = StoreUrl::to('shop/products/' . $product['slug']);
        }
    }

    private function findOriginalStartingPriceForProduct($product_id)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            return $product->getOriginalStartingAtPrice();
        }
        return null;
    }

    private function findStartingPriceForProduct($product_id)
    {
        $product = CatalogProduct::findOne($product_id);
        if ($product) {
            return $product->getPrice();// getStartingAtPrice();
        }
        return null;
    }

    private function findImageForProduct($product_id)
    {
        $image = CatalogProductGallery::getImages($product_id);
        $image = Assets::productResource($image);

        return $image;
    }

    private function findCategoryForProduct($product_id)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT name
            FROM catalog_category
            LEFT JOIN catalog_category_product ON catalog_category_product.category_id = catalog_category.id
            WHERE catalog_category_product.product_id = $product_id
            ");
        $category = $command->queryOne();

        return $category ? $category['name'] : null;
    }

    private function findBrandForProduct($product_id)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT name
            FROM catalog_brand
            LEFT JOIN catalog_product ON catalog_product.brand_id = catalog_brand.id
            WHERE catalog_product.id = $product_id
            ");
        $brand = $command->queryOne();

        return $brand ? $brand['name'] : null;
    }

    private function sortProducts($products)
    {
        $products = $products->all();

        //get catalog values
        $product_ids = ArrayHelper::getColumn($products, 'id');

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
        SELECT id, store_id, product_id
        FROM `catalog_attribute_value`
        WHERE `id` IN (
                    SELECT `id`
            FROM `catalog_attribute_value`
            WHERE (`attribute_id` IN (SELECT `id`
                FROM `catalog_attribute`
                WHERE `slug` = :sort_type))
            AND `product_id` IN (" . implode(',', $product_ids) . ")
        )
        GROUP BY product_id DESC;", [
            ':sort_type' => $this->sort_type
        ]);
        $result_ids = ArrayHelper::getColumn($command->queryAll(), 'id');

        $catalog_attribute_values = CatalogAttributeValue::find()
            ->where([
                'id' => $result_ids
            ])
            ->orderBy("value $this->sort_order")
            ->all();
        $catalog_attribute_values = ArrayHelper::getColumn($catalog_attribute_values, 'product_id');


        $output = CatalogProduct::find()->where([
            'id' => $catalog_attribute_values
        ])
            ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $catalog_attribute_values) . ')')])
            ->limit($this->per_page)
            ->offset($this->page * $this->per_page)
            ->all();

        return $output;
    }

    public function getProducts($filters = [])
    {

        if (is_array($filters)) {
            if (array_key_exists('category', $filters)) {

                if ($filters['category'] == 'new' || $filters['category'] == 'sale') {
                    $this->_products = $this->findAllProductsByAttribute($filters['category']);
                } else {
                    $this->_products = $this->findAllProductsByCategory($filters['category']);
                }

            }
            if (array_key_exists('brand', $filters)) {
                $this->filterByBrand($filters['brand']);
            }

        }
        $this->setAttributesForProducts();
        //@TODO filter out NULL prices
        $temp_arr = [];

        foreach ($this->_products as $product) {

            array_push($temp_arr, $product);
        }
        $this->_products = $temp_arr;

        return count($this->_products) > 0 ? $this->_products : [];
    }

    public function filterByCategory($category)
    {
        //get category
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT name
            FROM `catalog_category`
            WHERE `slug` = '$category'
            ");
        $category = $command->queryOne();

        $temp_arr = [];
        if ($category) {
            foreach ($this->_products as $product) {
                if ($product['category'] == $category['name']) {
                    array_push($temp_arr, $product);
                }
            }
        }
        $this->_products = $temp_arr;
    }

    public function filterByBrand($brand)
    {
        //get brand
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT name
            FROM `catalog_brand`
            WHERE `slug` = '$brand'
            ");
        $brand = $command->queryOne();

        $temp_arr = [];
        if ($brand) {
            foreach ($this->_products as $product) {
                if ($product['brand'] == $brand['name']) {
                    array_push($temp_arr, $product);
                }
            }
        }
        $this->_products = $temp_arr;
    }

    public function filterByAttribute($key, $value)
    {
        $temp_arr = [];

        if (is_string($value)) {
            $value = strtolower($value);
        }

        foreach ($this->_products as $product) {
            $compare_val = $product['attributes'][$key]['value'];
            if (is_string($compare_val)) {
                $compare_val = strtolower($compare_val);
            }
            if ($compare_val == $value) {
                array_push($temp_arr, $product);
            }
        }
        $this->_products = $temp_arr;
    }


}