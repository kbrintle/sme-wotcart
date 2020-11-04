<?php

namespace backend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeType;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


/**
 * Class AttributeForm
 * @package backend\models
 */
class AttributeForm extends Model{
    public $label;
    public $category_id;
    public $is_filterable;
    public $is_active;
    public $type_id;
    public $visible_on;
    public $is_editable;
    public $product_view_sort;
    public $is_product_view;
    public $filter_sort;

    public $attribute_options;

    private $_id;
    private $catalog_attribute;
    private $selectable_types = [2, 10];

    public function rules(){
        return [
            [['label'], 'string'],
            [['category_id', 'type_id', 'visible_on', 'filter_sort', 'product_view_sort'], 'integer'],
            [['is_editable', 'is_active', 'is_filterable', 'is_product_view'], 'boolean'],
            [['attribute_options', 'product_view_sort', 'is_product_view' ], 'safe'],
            [['label', 'type_id', 'category_id'], 'required']
        ];
    }


    /**
     * @param null $catalog_attribute
     */
    public function init(){
        $this->catalog_attribute = new CatalogAttribute();
    }

    /**
     * @param $catalog_attribute
     */
    public function loadAttribute($catalog_attribute){
        $this->catalog_attribute    = $catalog_attribute;

        $this->_id                  = $catalog_attribute->id;
        $this->label                = $catalog_attribute->label;
        $this->category_id          = $catalog_attribute->category_id;
        $this->is_filterable        = $catalog_attribute->is_filterable;
        $this->filter_sort          = $catalog_attribute->filter_sort;
        $this->is_product_view      = $catalog_attribute->is_product_view;
        $this->product_view_sort    = $catalog_attribute->product_view_sort;
        $this->is_active            = $catalog_attribute->is_active;
        $this->type_id              = $catalog_attribute->type_id;
        $this->visible_on           = $catalog_attribute->visible_on;
        $this->is_editable          = $catalog_attribute->is_editable;
    }


    /**
     * @return bool
     */
    public function isSelectable(){
        if( in_array($this->type_id, $this->selectable_types) ){
            return true;
        }
        return false;
    }


    /**
     * @return mixed
     */
    public function getId(){
        return $this->_id;
    }

    /**
     * @return array
     */
    public function getAttributeTypes(){
        $attribute_types = CatalogAttributeType::find()
            ->where([
                'is_active' => true,
                'is_deleted' => false
            ])
            ->all();
        $output = ArrayHelper::map($attribute_types, 'id', 'type');

        return $output;
    }

    /**
     * @return array
     */
    public function getSetCategories(){
        $categories = CatalogAttributeSetCategory::find()
            ->where([
                'is_active' => true,
                'is_deleted' => false
            ])
            ->all();
        $output = ArrayHelper::map($categories, 'id', 'label');

        return $output;
    }

    /**
     * @return array|\common\models\catalog\query\CatalogAttributeOption[]
     */
    public function getAttributeOptions(){
        $output = [];

        if($this->catalog_attribute->id){
            $output = CatalogAttributeOption::find()
                ->where([
                    'attribute_id' => $this->catalog_attribute->id
                ])
                ->orderBy('order ASC')
                ->all();
        }

        return $output;
    }


    private function removeAttributeOptions(){
        if( $this->catalog_attribute->id ){
            $command = Yii::$app->db->createCommand();
            $command->delete('catalog_attribute_option', [
                'attribute_id' => $this->catalog_attribute->id
            ]);
            $command->execute();
        }
    }

    public function setAttributeOptions(){

        if( $this->catalog_attribute
            && $this->attribute_options
            && $this->isSelectable() ){
            $catalog_attribute_type = CatalogAttributeType::findOne($this->catalog_attribute->type_id);

            if( $catalog_attribute_type ){
                $format = $catalog_attribute_type->format;
                if( $format == 'select' || $format == 'multiple' ){
                    //$this->removeAttributeOptions();
                    $i=1;
                    foreach( $this->attribute_options as $k => $v ){
                        $attribute_option = CatalogAttributeOption::find()->where(['id'=>$k])->one();

                        if($attribute_option){
                            $attribute_option->value = $v;
                            $attribute_option->order = $i;
                            $attribute_option->save(false);

                        }else{
                            $catalogAttributeOption = new CatalogAttributeOption();
                            $catalogAttributeOption->attribute_id = $this->catalog_attribute->id;
                            $catalogAttributeOption->store_id = CurrentStore::getStoreId();
                            $catalogAttributeOption->value = $v;
                            $catalogAttributeOption->created_at = time();
                            $catalogAttributeOption->order = $i;
                            $catalogAttributeOption->save(false);

                        }
                        $i++;
                    }
                }
            }
        }

    }


    public function save($new=false){
        if($new){
            $this->catalog_attribute->slug        = preg_replace("/(\W)+/", "-", strtolower($this->label));
            $this->catalog_attribute->store_id    = CurrentStore::getStoreId();
            $this->catalog_attribute->created_at  = time();
        }

        $this->catalog_attribute->label              = $this->label;
        $this->catalog_attribute->category_id        = $this->category_id;
        $this->catalog_attribute->is_filterable      = $this->is_filterable;
        $this->catalog_attribute->is_active          = $this->is_active;
        $this->catalog_attribute->type_id            = $this->type_id;
        $this->catalog_attribute->visible_on         = $this->visible_on;
        $this->catalog_attribute->filter_sort        = $this->filter_sort;
        $this->catalog_attribute->product_view_sort  = $this->product_view_sort;
        $this->catalog_attribute->is_product_view    = $this->is_product_view;
        $this->catalog_attribute->is_editable        = $this->is_editable;

        if( $this->catalog_attribute->save() ){
            $this->setAttributeOptions();
            return true;
        }

        return false;
    }

}