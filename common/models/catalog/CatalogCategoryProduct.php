<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogCategoryProductQuery;
use Yii;

/**
 * This is the model class for table "catalog_category_product".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $product_id
 * @property integer $sort
 * @property integer $created_at
 * @property CatalogCategory $category
 * @property CatalogProduct $product
 */
class CatalogCategoryProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id', 'created_at'], 'required'],
            [['category_id', 'product_id', 'sort', 'created_at'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CatalogCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    public static function find()
    {
        return new CatalogCategoryProductQuery(get_called_class());
    }
}
