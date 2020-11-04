<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "catalog_product_meta_data".
 *
 * @property int $id
 * @property string $sku
 * @property string $keywords
 * @property string $description
 */
class CatalogProductMetaData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catalog_product_meta_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keywords', 'description'], 'string'],
            [['sku'], 'string', 'max' => 155],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Sku',
            'keywords' => 'Keywords',
            'description' => 'Description',
        ];
    }
}