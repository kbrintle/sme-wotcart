<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catalog_storebrands".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $brands_ids
 */
class CatalogStorebrands extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_storebrands';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'required'],
            [['store_id'], 'integer'],
            [['brands_ids'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'brands_ids' => 'Brands Ids',
        ];
    }
}