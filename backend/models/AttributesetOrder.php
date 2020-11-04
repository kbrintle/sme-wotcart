<?php

namespace backend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogProduct;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class AttributesetOrder extends Model{

    public function rules(){

    }


    public function unlinkAll($store_id){
        $connection = Yii::$app->getDb();
        return $connection->createCommand()->delete('catalog_attribute__order', "store_id = $store_id")->execute();
    }
    public function link($store_id, $attribute_id, $order){
        $connection = Yii::$app->getDb();
        return $connection->createCommand()->insert('catalog_attribute__order', [
                'store_id'      => $store_id,
                'attribute_id'  => $attribute_id,
                'order'         => $order
            ])->execute();
    }

    public static function update($order){
        $store_id = CurrentStore::getStoreId();
        AttributesetOrder::unlinkAll($store_id);

        if( $store_id ){
            if( count($order) > 0 ){
                foreach($order as $o){
                    if( array_key_exists('value', $o)
                        && array_key_exists('order', $o)){
                        $attribute = CatalogAttribute::findOne($o['value']);
                        if( $attribute )
                            AttributesetOrder::link($store_id, $attribute->id, $o['order']);
                    }
                }
            }
        }
    }

    public static function sortAttributes($attributes){
        $current_store = CurrentStore::getStore();

        $output = $attributes;      //not sorted yet, return the original set
        if( $current_store ){
            $sorted_attributes = [];
            $current_attributes = $current_store->catalogAttributes;

            $sorted_map     = ArrayHelper::getColumn($current_attributes, 'id');
            $unsorted_map   = ArrayHelper::getColumn($attributes, 'id');

            $difference_map = array_diff($unsorted_map, $sorted_map);

            //push found sorted attributes into array
            foreach($sorted_map as $v){
//                var_dump($v);
//                echo '<hr>';
                foreach($attributes as $attribute){
                    if($attribute->id == $v)
                        array_push($sorted_attributes, $attribute);
                }
            }

            //push the unsorted attributes in (leftovers)
            foreach($difference_map as $v){
                foreach($attributes as $attribute){
                    if($attribute->id == $v)
                        array_push($sorted_attributes, $attribute);
                }
            }

            $output = $sorted_attributes;
        }

        return $output;
    }

}