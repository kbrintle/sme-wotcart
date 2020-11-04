<?php

namespace common\models\catalog\query;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\components\CurrentStore;
use common\models\catalog\CatalogStoreProduct;
use common\models\core\Store;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the ActiveQuery class for [[CatalogProduct]].
 *
 * @see CatalogProduct
 */
class CatalogProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function storeProducts(){
        $this->where([
                'parent_id' => null
            ])
            ->andWhere(['IN', 'brand_id', CatalogBrandStore::find()
                ->select('brand_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
            ])
            ->andWhere(['NOT IN', 'id', CatalogCategoryProduct::find()
                ->select('product_id')
                ->where(['category_id' => CatalogCategory::find()
                    ->select('id')
                    ->where(['slug' => 'boxspring'])
                ])
            ])
            // Todo: for now, disregard visibility when displaying products
//            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
//                ->select('product_id')
//                ->where(['store_id' => CurrentStore::getStoreId()])
//                ->andWhere([
//                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'visible'])->id,
//                    'value'        => true
//                ])
//            ])
            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                    'value'        => true
                ])
            ])
            ->limit(16); // Todo: remove this hacky demo-only stuff

        return $this;
    }

    public function storeFeaturedProducts() {

        $session = Yii::$app->session;
        $offset = 0;
        if (isset($session['featured_offset'])) {
            $offset = $session['featured_offset'];
        }

        $this->where([
                'parent_id' => null
            ])
            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'featured'])->id,
                    'value'        => true
                ])
            ])
            ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                    'value'        => true
                ])
            ])
            ->orderBy(['created_at' => SORT_DESC])->offset($offset)
            ->limit(4);

        $offset++;
        $session['featured_offset'] = $offset;

        return $this;
    }

    public function storeHasProduct($slug) {
        return $this->where([
            //'parent_id' => null,
            'slug'      => $slug
        ])
        ->andWhere(['IN', 'id', CatalogStoreProduct::find()
            ->select('product_id')
            ->where(['store_id' => CurrentStore::getStoreId()])
        ]);
    }

    public function productGrid(){
        $current_store_id = CurrentStore::getStoreId();
        if($current_store_id == null){
            $current_store_id = 0;
        }

        if( is_numeric($current_store_id) ){
            $connection = Yii::$app->getDb();

            if( $current_store_id == 0 ){
                $command = $connection->createCommand("
                    SELECT *
                    FROM `catalog_product`
                    WHERE `store_id` = 0
                ");
                $result = $command->queryAll();
                return $result;
            }

            //$compatibleBrands = CatalogAttribute::getAttributeBySlug('compatible-brands');
            $command = $connection->createCommand("
                SELECT *
                FROM   `catalog_product`
                WHERE  `id` IN (
                    SELECT `id` 
                    FROM   `catalog_store_product` 
                    WHERE  `store_id` = '$current_store_id')

                   ");
            $result = $command->queryAll();
            return $result;
        }

        return [];
    }

    public function filterByAttributeValue($attribute_slug, $attribute_value){
        $catalog_attribute = CatalogAttribute::find()
            ->select('id')
            ->where([
                'slug' => $attribute_slug
            ]);
        $catalog_attribute_value = CatalogAttributeValue::find()
            ->select('product_id')
            ->where([
                'attribute_id'  => $catalog_attribute,
                'value'         => $attribute_value
            ]);

        $this->andWhere([
            'id' => $catalog_attribute_value
        ]);

        return $this;
    }

    public function filterByPrice($price_range=[]){
        $catalog_attribute = CatalogAttribute::find()
            ->select('id')
            ->where([
                'slug' => 'price'
            ]);

        if( array_key_exists('min', $price_range)
            && array_key_exists('max', $price_range) ){         //min-max range

            $catalog_attribute_value = CatalogAttributeValue::find()
                ->select('product_id')
                ->where([
                    'attribute_id'  => $catalog_attribute
                ])
                ->andWhere(['between', 'value', $price_range['min'], $price_range['max']]);
            $this->andWhere([
                'id' => $catalog_attribute_value
            ]);

        }

        if( array_key_exists('min', $price_range)
            && !array_key_exists('max', $price_range) ){        //min-∞ range

            $catalog_attribute_value = CatalogAttributeValue::find()
                ->select('product_id')
                ->where([
                    'attribute_id'  => $catalog_attribute
                ])
                ->andWhere(['>', 'value', $price_range['min']]);
            $this->andWhere([
                'id' => $catalog_attribute_value
            ]);

        }

        if( !array_key_exists('min', $price_range)
            && array_key_exists('max', $price_range) ){         //0-max range

            $catalog_attribute_value = CatalogAttributeValue::find()
                ->select('product_id')
                ->where([
                    'attribute_id'  => $catalog_attribute
                ])
                ->andWhere(['<', 'value', $price_range['max']]);
            $this->andWhere([
                'id' => $catalog_attribute_value
            ]);

        }

        return $this;
    }

    public function filterByStartingPrice($price_range=[]){
        $products = CatalogProduct::find()->storeProducts()->all();

        $filtered_products = [];

        foreach($products as $product){
            $starting_price = $product->startingAtPrice;
            if( array_key_exists('min', $price_range)
                && array_key_exists('max', $price_range) ){         //min-max range

                if( $starting_price > $price_range['min']
                    && $starting_price < $price_range['max'] ){
                    array_push($filtered_products, $product);
                }
            }

            if( array_key_exists('min', $price_range)
                && !array_key_exists('max', $price_range) ){        //min-∞ range
                if( $starting_price > $price_range['min'] ){
                    array_push($filtered_products, $product);
                }
            }

            if( !array_key_exists('min', $price_range)
                && array_key_exists('max', $price_range) ){         //0-max range
                if( $starting_price < $price_range['max'] ){
                    array_push($filtered_products, $product);
                }
            }
        }

        if( count($filtered_products) > 0 ){
            return $this->andWhere([
                'id' => ArrayHelper::getColumn($filtered_products, 'id')
            ]);
        }
        return $this;
    }

}
