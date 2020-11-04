<?php

namespace common\models\store;

use Yii;
use common\models\core\Store;
use common\models\catalog\CatalogProduct;

/**
 * This is the model class for table "catalog_store_product".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property Store $store
 * @property CatalogProduct $product
 */
class StoreProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_store_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'product_id'], 'required'],
            [['store_id', 'product_id', 'created_at', 'is_active', 'is_deleted'], 'integer'],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
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
            'store_id' => 'Store ID',
            'product_id' => 'Product ID',
            'created_at' => 'Created At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }
}
