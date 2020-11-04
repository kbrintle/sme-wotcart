<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogBrandStoreQuery;
use Yii;

/**
 * This is the model class for table "catalog_brand_store".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property integer $store_id
 * @property integer $created_at
 */
class CatalogBrandStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_brand_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'created_at'], 'required'],
            [['brand_id', 'store_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'store_id' => 'Store ID',
            'created_at' => 'Created At',
        ];
    }

    public function getStore(){
        return $this->hasOne(CatalogBrandStore::className(), ['id' => 'store_id']);
    }

    public static function find()
    {
        return new CatalogBrandStoreQuery(get_called_class());
    }
}
