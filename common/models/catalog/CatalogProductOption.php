<?php

namespace common\models\catalog;

use Yii;
use common\models\catalog\CatalogProductOptionValue;
use common\components\CurrentStore;

/**
 * This is the model class for table "catalog_product_option".
 *
 * @property int $option_id Option ID
 * @property int $store_id Store ID
 * @property int $product_id Product ID
 * @property string $type Type
 * @property string $title Title
 * @property int $is_required Is Required
 * @property string $sku SKU
 * @property int $sort_order Sort Order
 *
 * @property CatalogProductOptionPrice[] $catalogProductOptionPrices
 * @property CatalogProductOptionTitle[] $catalogProductOptionTitles
 * @property CatalogProductOptionTypeValue[] $catalogProductOptionTypeValues
 */
class CatalogProductOption extends \yii\db\ActiveRecord
{
    const TYPE_DROPDOWN = 'dropdown';
    const TYPE_RADIO    = 'radio';
    const TYPE_CHECKBOX = 'checkbox';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'store_id', 'is_required', 'sort_order'], 'integer'],
            [['type'], 'required'],
            [['type'], 'string', 'max' => 50],
            [['sku'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'type' => 'Type',
            'title' => 'Title',
            'is_required' => 'Is Required',
            'sku' => 'Sku',
            'sort_order' => 'Sort Order',
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProductOptionPrices()
    {
        return $this->hasMany(CatalogProductOptionPrice::className(), ['option_id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProductOptionTitles()
    {
        return $this->hasMany(CatalogProductOptionTitle::className(), ['option_id' => 'option_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProductOptionTypeValues()
    {
        return $this->hasMany(CatalogProductOptionTypeValue::className(), ['option_id' => 'option_id']);
    }

    public static function getOptions($product_id, $store_id){
        return CatalogProductOption::find()->where(['product_id'=>$product_id])->andWhere(["IN",'store_id',[$store_id, '0']])->orderBy('sort_order')->all();
    }

    public static function getOptionValues($option_id){

        $obj = [];
        $skus = [];
        $optionValuesQuery = CatalogProductOptionValue::find()->where(['option_id' => $option_id])->andWhere(['store_id' => CurrentStore::getStoreId()])->orderBy(['sort_order' => SORT_ASC])->all();
        foreach ($optionValuesQuery as $id => $optionValue) {
            $objValue = new \stdClass();
            $objValue->option_value_id = $optionValue->option_value_id;
            $objValue->title = $optionValue->title;
            $objValue->sku = $optionValue->sku;
            $objValue->price = $optionValue->price;
            $objValue->price_type = $optionValue->price_type;
            $objValue->sort_order = $optionValue->sort_order;
            $obj[] = $objValue;
            $skus[] = $optionValue->sku;
        }
        if (CurrentStore::getStoreId() != CurrentStore::ALL) {
            $optionValuesQuery = CatalogProductOptionValue::find()->where(['option_id' => $option_id])->andWhere(['store_id' => '0'])->andWhere(['NOT IN', 'sku', $skus])->orderBy(['sort_order' => SORT_ASC])->all();
            foreach ($optionValuesQuery as $id => $optionValue) {
                $objValue = new \stdClass();
                $objValue->option_value_id = $optionValue->option_value_id;
                $objValue->title = $optionValue->title;
                $objValue->sku = $optionValue->sku;
                $objValue->price = $optionValue->price;
                $objValue->price_type = $optionValue->price_type;
                $objValue->sort_order = $optionValue->sort_order;
                $obj[] = $objValue;
            }
        }
        uasort($obj, "self::sort_order");
        return $obj;
    }

    public static function sort_order($a, $b)
    {
        if ($a->sort_order == $b->sort_order) return 0;
        return ($a->sort_order < $b->sort_order) ? -1 : 1;
    }
}