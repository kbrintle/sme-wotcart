<?php

namespace common\models\store;

use Yii;
use common\models\store\query\StoreCouponQuery;

/**
 * This is the model class for table "store_coupon".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $image
 * @property integer $is_deleted
 * @property integer $is_active
 * @property integer $sort
 * @property integer $created_at
 * @property integer $modified_at
 */
class StoreCoupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_deleted', 'is_active', 'sort', 'created_at', 'modified_at'], 'integer'],
            [['image'], 'string', 'max' => 255],
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
            'image' => 'Image',
            'is_deleted' => 'Is Deleted',
            'is_active' => 'Is Active',
            'sort' => 'Sort',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * @inheritdoc
     * @return StoreCouponQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreCouponQuery(get_called_class());
    }
}