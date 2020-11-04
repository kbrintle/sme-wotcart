<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogFeatureQuery;
use Yii;

/**
 * This is the model class for table "catalog_product_feature".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $description
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $option_id
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogProduct $catalogProduct
 * @property CatalogProduct[] $ids
 * @property CatalogBrand[] $ids0
 */
class CatalogFeature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_feature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at'], 'required'],
            [['store_id', 'created_at', 'modified_at', 'option_id', 'is_active', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(CatalogProduct::className(), ['parent_id' => 'id'])->viaTable('catalog_product', ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds0()
    {
        return $this->hasMany(CatalogBrand::className(), ['id' => 'id'])->viaTable('catalog_product', ['id' => 'id']);
    }

    public static function find()
    {
        return new CatalogFeatureQuery(get_called_class());
    }
}
