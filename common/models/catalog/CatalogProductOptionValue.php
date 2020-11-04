<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product_option_type_title".
 *
 *
 *
 * @property int $option_value_id Option Value ID
 * @property int $option_id Option ID
 * @property int $store_id Store ID
 * @property int $option_type_title_id Option Type Title ID
 * @property int $option_type_id Option Type ID
 * @property string $sku SKU
 * @property dec $price Price
 * @property string $title Title
 * @property int $sort_order Sort Order
 *
 * @property CatalogProductOptionTypeValue $optionType
 */
class CatalogProductOptionValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_option_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option_value_id', 'store_id'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['option_value_id'], 'unique', 'targetAttribute' => ['option_value_id']],
            //[['option_value_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProductOptionTypeValue::className(), 'targetAttribute' => ['option_value_id' => 'option_value_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_value_id' => 'Option Value ID',
            'option_id' => 'Option ID',
            'store_id' => 'Store ID',
            'title' => 'Title',
            'sku' => 'SKU',
            'price' => 'Price',
            'price_type' => 'Price Type',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionType()
    {
        return null;
        //return $this->hasOne(CatalogProductOptionTypeValue::className(), ['option_type_id' => 'option_type_id']);
    }
}
