<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogProductAttributeSetQuery;
use Yii;

/**
 * This is the model class for table "catalog_product_attribute_set".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $store_id
 * @property integer $set_id
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogProduct $product
 * @property CatalogAttributeSet $set
 */
class CatalogProductAttributeSet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_attribute_set';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'set_id', 'created_at'], 'required'],
            [['product_id', 'store_id', 'set_id', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['set_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttributeSet::className(), 'targetAttribute' => ['set_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'set_id' => 'Set ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
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
    public function getSet()
    {
        return $this->hasOne(CatalogAttributeSet::className(), ['id' => 'set_id']);
    }

    public static function find()
    {
        return new CatalogProductAttributeSetQuery(get_called_class());
    }
}
