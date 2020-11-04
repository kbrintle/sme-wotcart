<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "promotion_store_promotion".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $promotion_id
 * @property integer $created_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionStorePromotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_store_promotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'promotion_id', 'created_at'], 'required'],
            [['store_id', 'promotion_id', 'created_at', 'is_active', 'is_deleted'], 'integer'],
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
            'promotion_id' => 'Promotion ID',
            'created_at' => 'Created At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\query\PromotionStorePromotionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\promotion\query\PromotionStorePromotionQuery(get_called_class());
    }
}
