<?php

namespace common\models\catalog\query;

use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogProduct;
use frontend\models\StoreFilters;
use Yii;
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\models\core\Store;
use common\models\catalog\CatalogStoreProduct;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[CatalogStoreProduct]].
 *
 * @see CatalogStoreProduct
 */
class CatalogStoreProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogStoreProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogStoreProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function storeProducts(){
        return CatalogStoreProduct::find()
            ->where(['store_id' => CurrentStore::getStoreId()])
            ->andWhere(['IN', 'product_id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'visible'])->id,
                    'value' => true
                ])

            ])
            ->andWhere(['IN', 'product_id', CatalogAttributeValue::find()
                ->select('product_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
                ->andWhere([
                    'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                    'value' => true
                  ])
              ])
         ->all();
    }
//    public function storeProducts()
//    {
//        $query=  CatalogProduct::find()
//            ->where(['IN', 'id', CatalogStoreProduct::find()
//                ->select('product_id')
//                ->where(['store_id' => CurrentStore::getStoreId()])
//                ->andWhere(['IN', 'product_id', CatalogAttributeValue::find()
//                    ->select('product_id')
//                    ->where(['store_id' => CurrentStore::getStoreId()])
////                    ->andWhere([
////                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'active']),
////                        'value' => true
////                     ])
//                    ->andWhere(['IN', 'id', CatalogAttributeValue::find()
//                        ->select('id')
//                        ->where([
//                            'attribute_id' => CatalogAttribute::findOne(['slug' => 'visible']),
//                            'value' => true
//                                ])
//                        ])
//                ])
//            ])
//        ->all();
//        return $query;
//        //print_r($query); die;
//    }

    private function parseFilterValues($filters=[]){
        $parse_keys = [];

        /** get all possible filters */
        $possible_filters = CatalogAttribute::find()->where([
//            'type_id'       => 2,
            'is_filterable' => 1
        ]);
        $possible_filters = $possible_filters->all();

        /** filter $query by keys that match the $possible_filters */
        foreach($filters as $k=>$v){
            foreach($possible_filters as $filter){
                if($k == $filter->label){
                    $parse_keys[$k] = [
                        'value'     => $v,
                        'attribute' => $filter
                    ];
                }
            }
        }

        return [
            'filters'    => $parse_keys,
            'attributes' => $possible_filters
        ];
    }

    private function getStoreCatalogAttributes($attributes=[]){
//        $store_id = 28;
        $store_id = CurrentStore::getStoreId();


        $store_attributes   = CatalogAttributeValue::find()
            ->where(['in', 'attribute_id', ArrayHelper::getColumn($attributes, 'id')])
            ->andWhere(['store_id' => $store_id])
            ->all();
        $store_attributes_ids = ArrayHelper::getColumn($store_attributes, 'attribute_id');
        $default_attributes = CatalogAttributeValue::find()
            ->where(['in', 'attribute_id', ArrayHelper::getColumn($attributes, 'id')])
            ->andWhere(['not in', 'attribute_id', $store_attributes_ids])
            ->andWhere(['store_id' => 0])
            ->all();

        $output_attributes = array_merge($store_attributes, $default_attributes);

        return $output_attributes;
    }

    private function findInModels($models, $key, $value){
        foreach($models as $model){
            if($model->{$key} == $value){
                return $model;
            }
        }
    }


    public function byStore($store_id=null){
        $model_class = $this->modelClass;
        $table_name = $model_class::tableName();

        if(!$store_id){
            if( CurrentStore::isNone() || CurrentStore::isNational() ){
                $store_id = Store::NO_STORE;
            }else{
                $store_id = CurrentStore::getStoreId();
            }
        }

        return $this->andWhere([
            "$table_name.store_id" => $store_id
        ]);
    }

    public function byCategory($slug=null){
        if($slug){
            $catalog_category = CatalogCategory::find()->where([
                'slug' => $slug
            ])->one();

            if ($catalog_category) {
                $catalog_category_products = CatalogCategoryProduct::find()->where([
                    'category_id' => $catalog_category->id
                ])->all();

                $this->andWhere(['product_id' => ArrayHelper::getColumn($catalog_category_products, 'product_id')]);
            }
        }

        return $this;
    }





    /**
     * @NOTE Due to all the conditional relationships you CANNOT use this as a normal ActiveQuery.
     *       This will always return the results - it should be used as a replacement for ->all().
     *
     * @param array $filters
     * @return mixed
     */
    public function facetedSearch( $filters=[] ){
        /** filter possible filters by catalog_attribute = {is_filterable:true} */
        $filters = $this->parseFilterValues($filters);

        $filtered_products = [];
        foreach($this->all() as $product){
            $add_count = 0;
            foreach($filters['filters'] as $k=>$v){

                if(CurrentStore::isNone() || CurrentStore::isNational()){
                    $pid = $product->id;
                }else{
                    $pid = $product->product_id;
                }
                $attribute = CatalogAttribute::find()->where([
                    'label' => $k
                ])->select('slug')->one();
                $attribute_slug = $attribute->slug;

                $val = CatalogProduct::getAttributeValue($pid, $attribute_slug);

                if($k=='Price') {
                    $price_add_count = 0;

                    //check for price first
                    $price_add_count = $price_add_count + $this->checkForPrice($v, $val, $product, $attribute_slug);

                    //now check for 'Special Price'
                    $special_price_value    = CatalogProduct::getAttributeValue($pid, 'special-price');
                    $price_add_count = $price_add_count + $this->checkForPrice($v, $special_price_value, $product, 'special-price');
                    if($price_add_count){
                        $add_count++;
                    }
                }else{
                    if( in_array($val, $v['value']) ){
                        $add_count++;
                    }
                    if(!$add_count){
                        $child_attribute_values = $this->getChildAttributeValues($product, $attribute_slug);
                        if( count($child_attribute_values) > 0 ){
                            foreach($child_attribute_values as $child_attribute_value){
                                if( in_array($child_attribute_value, $v['value']) ){
                                    $add_count++;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            if( $add_count == count($filters['filters']) ){
                array_push($filtered_products, $product);
            }
        }
        return $filtered_products;
    }

    private function getChildAttributeValues($product, $attribute){
        $output = [];

        $child_products = CatalogProduct::find()
            ->where([
                'parent_id' => $product->product_id
            ])->all();

        foreach($child_products as $child_product){
            array_push($output, CatalogProduct::getAttributeValue($child_product->id, $attribute));
        }

        return $output;
    }
    private function checkForPrice($v, $val, $product, $attribute_slug){
        $add_count = 0;

        foreach($v['value'] as $v_value){
            if( StoreFilters::determinePriceRange($v_value, $val) ){
                $add_count++;
                break;
            }
        }

        if(!$add_count){
            foreach($v['value'] as $v_value){
                $child_attribute_values = $this->getChildAttributeValues($product, $attribute_slug);

                if (count($child_attribute_values) > 0) {
                    foreach ($child_attribute_values as $child_attribute_value) {
                        if( StoreFilters::determinePriceRange($v_value, $child_attribute_value) ){
                            $add_count++;
                            break;
                        }
                    }
                }
            }
        }

        return $add_count;
    }
}
