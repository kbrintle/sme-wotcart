<?php

namespace common\models\catalog;

use common\components\CurrentStore;
use common\models\catalog\query\CatalogAttributeValueQuery;
use common\models\core\Store;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "catalog_attribute_value".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $store_id
 * @property integer $product_id
 * @property string $value
 * @property CatalogProduct $product
 * @property CatalogAttribute $id0
 * @property CatalogAttributeSet $id1
 */
class CatalogAttributeValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'product_id', 'value'], 'required'],
            [['attribute_id', 'store_id', 'product_id'], 'integer'],
            [['value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'attribute_id' => 'Attribute ID',
            'store_id'     => 'Store ID',
            'product_id'   => 'Product ID',
            'value'        => 'Value',
        ];
    }

    public static function storeValue($attribute_slug, $product_id, $all = false) { // TODO CPM deprecate
        $attributeModel = CatalogAttribute::getAttributeBySlug($attribute_slug);
        if ($attributeModel) {
            $attribute = CatalogAttributeValue::find()
                ->where([
                    'store_id'     => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'attribute_id' => $attributeModel->id,
                    'product_id'   => $product_id,
                ])
                ->orderBy(['store_id' => SORT_DESC]);

            if ($all) {
                $attributes = $attribute->all();
                if ($attributes) {
                    foreach ($attributes as $attribute) {
                        $values[] = $attribute->value;
                    }
                }
                return (empty($values) ? [] : $values);
            } else {
                $attribute = $attribute->limit(1)->all();
                return (empty($attribute) ? null : $attribute[0]->value);
            }
        }
        return [];
    }


    public static function setValue($attribute_slug, $value, $product_id, $store_id = 0) {
        $store_id       = $store_id ? $store_id : [Store::NO_STORE, CurrentStore::getStoreId()];
        $attributeModel = CatalogAttribute::getAttributeBySlug($attribute_slug);

        $attribute = CatalogAttributeValue::find()
            ->where([
                'store_id'     => $store_id,
                'attribute_id' => $attributeModel->id,
                'product_id'   => $product_id,
            ])->one();

        if($value != null ){
            if(!isset($attribute)){
                $attribute = new CatalogAttributeValue();
                $attribute->store_id     = $store_id;
                $attribute->attribute_id = $attributeModel->id;
                $attribute->product_id   = $product_id;
                $attribute->value        = $value;
            }else{
                $attribute->value       = $value;
            }
            $attribute->save(false);
        }


        return (empty($attribute) ? null : $attribute->value);
    }

    public function getCatalogAttribute(){
        return $this->hasOne(CatalogAttribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(CatalogAttribute::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId1()
    {
        return $this->hasOne(CatalogAttributeSet::className(), ['id' => 'id']);
    }

    public function getDisplayValue() {
        //return relational value
        if ($this->catalogAttribute && $this->catalogAttribute->type_id == 2) {
            $catalog_attribute_option = CatalogAttributeOption::findOne([
                'id'            => $this->value,
                'attribute_id'  => $this->attribute_id
            ]);

            if($catalog_attribute_option){
                return $catalog_attribute_option->value;
            }
        }

        return $this->value;
    }

    public static function find()
    {
        return new CatalogAttributeValueQuery(get_called_class());
    }
}
