<?php

namespace common\models\catalog;

use Yii;
use common\models\customer\Customer;
use common\models\core\Store;
use common\models\catalog\CatalogProduct;

/**
 * This is the model class for table "catalog_product_review".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $rating
 * @property string $title
 * @property string $detail
 * @property integer $customer_id
 * @property integer $store_id
 * @property string $approved
 * @property integer $created_at
 */
class CatalogProductReview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'customer_id', 'store_id', 'created_at'], 'integer'],
            [['approved'], 'boolean'],
            [['detail'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title', 'rating', 'detail', 'customer_id', 'store_id', 'approved', 'created_at'], 'required'],
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
            'rating' => 'Rating',
            'title' => 'Title',
            'detail' => 'Detail',
            'customer_id' => 'Customer ID',
            'store_id' => 'Store ID',
            'approved' => 'Approved',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\catalog\models\query\CatalogProductReviewQuery the active query used by this AR class.
     */

    public static function find($overRideScope = false )
    {
        $query =  new \common\models\catalog\query\CatalogProductReviewQuery(get_called_class());

        if( !$overRideScope )
            $query->store();

        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProduct()
    {

        return CatalogProduct::getName($this->product_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
