<?php

namespace common\models\promotion;

use Yii;
use common\models\promotion\query\PromotionStoreCodeQuery;

/**
 * This is the model class for table "promotion_store_code".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $code_id
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionStoreCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_store_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'code_id', 'created_at'], 'required'],
            [['store_id', 'code_id', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
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
            'code_id' => 'Code ID',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @inheritdoc
     * @return PromotionStoreCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PromotionStoreCodeQuery(get_called_class());
    }
}
