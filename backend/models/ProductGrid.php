<?php

namespace backend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogProduct;
use Yii;
use yii\base\Model;
use yii\helpers\Json;

class ProductGrid extends Model{
    public $selected_products;
    public $selected_key;
    public $selected_value;

    public $switch_id;
    public $switch_key;
    public $switch_value;

    public $update_product;
    public $update_value;

    public $sort_type;
    public $sort_order;

    public $bulk_options = [
        [
            'key'   => 'status',
            'value' => 0,
            'label' => 'Set Inactive'
        ],
        [
            'key'   => 'status',
            'value' => 1,
            'label' => 'Set Active'
        ]
    ];

    public $search;

    public function rules(){
        return [
            [['switch_id'], 'integer'],
            [['selected_key', 'selected_value', 'update_value', 'sort_type', 'sort_order', 'switch_key', 'switch_value'], 'string'],
            [['selected_products', 'search'], 'safe']
        ];
    }


    /** ==========
     *
     * BULK UPDATE ACTIONS
     *
    ========== */
    public function updateAttribute(){
        if( $this->switch_id
            && $this->switch_key
            && isset($this->switch_value) ){

            //get the current attribute
            $attribute = CatalogAttribute::find()
                ->where([
                    'slug' => $this->switch_key
                ])
                ->one();

            $current_store_id = CurrentStore::getStoreId();
            if( !$current_store_id )
                $current_store_id = 0;

            if( $attribute ){               //if attribute exists, find corresponding product value for store
                $attribute_value = CatalogAttributeValue::find()
                    ->where([
                        'attribute_id'  => $attribute->id,
                        'store_id'      => $current_store_id,
                        'product_id'    => $this->switch_id
                    ])
                    ->one();

                $update_value = $this->switch_value;
                if($this->switch_key == 'active'){
                    $update_value = $this->switch_value ? '1' : '0';
                }

                if( $attribute_value ){     //if store value exists, update the value
                    $attribute_value->value = $update_value;
                }else{                      //if store value does not exist, create a new value record
                    $attribute_value = new CatalogAttributeValue();
                    $attribute_value->attribute_id = $attribute->id;
                    $attribute_value->store_id     = $current_store_id;
                    $attribute_value->product_id   = $this->switch_id;
                    $attribute_value->value        = $update_value;
                }

                if( $attribute_value->save() ){
                    return "$this->switch_key updated successfully";
                }else{
                    error_log(print_r($attribute_value->getErrors(), true), 0);
                    return "$this->switch_key failed to update";
                }
            }
        }
        return null;
    }



    /** ==========
     *
     * BULK UPDATE ACTIONS
     *
     ========== */
    public function bulkUpdate(){
        if( $this->selected_products
            && $this->selected_key
            && isset($this->selected_value) ){

            if( method_exists(get_class($this), "bulkUpdate_$this->selected_key") ){
                return $this->{"bulkUpdate_$this->selected_key"}();
            }

        }

        return null;
    }
    private function bulkUpdate_active(){
        $success    = [];
        $failure    = [];

        //get the current attribute
        $attribute = CatalogAttribute::find()
            ->where([
                'slug' => 'active'
            ])
            ->one();

        $current_store_id = CurrentStore::getStoreId();
        if( !$current_store_id )
            $current_store_id = 0;

        if( $attribute ){                   //if attribute exists, find corresponding product value for store
            foreach( $this->selected_products as $selected_product_id ){
                $attribute_value = CatalogAttributeValue::find()
                    ->where([
                        'attribute_id'  => $attribute->id,
                        'store_id'      => $current_store_id,
                        'product_id'    => $selected_product_id
                    ])
                    ->one();

                if( $attribute_value ){     //if store value exists, update the value
                    $attribute_value->value = $this->selected_value;
                }else{                      //if store value does not exist, create a new value record
                    $attribute_value = new CatalogAttributeValue();
                    $attribute_value->attribute_id = $attribute->id;
                    $attribute_value->store_id     = $current_store_id;
                    $attribute_value->product_id   = $selected_product_id;
                    $attribute_value->set_id       = 0;
                    $attribute_value->value        = $this->selected_value ? '1' : '0';
                    $attribute_value->created_at   = time();
                }

                if( $attribute_value->save() ){
                    array_push($success, $selected_product_id);
                }else{
                    array_push($failure, $selected_product_id);
                }
            }

            return Json::encode([
                'success' => $success,
                'failure' => $failure
            ]);
        }

        return "No Attribute found";
    }
    private function bulkUpdate_delete(){
        $success    = [];
        $failure    = [];

        $catalog_products = CatalogProduct::find()
            ->where([
                'id' => $this->selected_products
            ])
            ->all();
        foreach($catalog_products as $catalog_product){
            $product_id = $catalog_product->id;

            if( $catalog_product->delete() )
                array_push($success, $product_id);
            else
                array_push($delete, $product_id);
        }

        return Json::encode([
            'success' => $success,
            'failure' => $failure
        ]);
    }

    /** ==========
     *
     * DELETE ACTIONS
     *
     ========== */
    public function deleteSingle(){
        if( $this->switch_id  ){
            $product = CatalogProduct::findOne($this->switch_id);
            if( $product ){
                $product_id = $product->id;
                if( $product->delete() )
                    return $product_id;
            }
        }
        return null;
    }
}