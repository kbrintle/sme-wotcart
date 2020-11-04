<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "promotion_discount_condition".
 *
 * @property integer $id
 * @property integer $discount_id
 * @property string $condition
 * @property string $key
 * @property string $operation
 * @property string $value
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionDiscountCondition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_discount_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_id', 'condition', 'key', 'operation', 'value', 'created_at'], 'required'],
            [['discount_id', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['condition', 'key', 'operation', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'discount_id' => 'Discount Set ID',
            'condition' => 'Condition',
            'key' => 'Key',
            'operation' => 'Operation',
            'value' => 'Value',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\query\PromotionDiscountConditionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\promotion\query\PromotionDiscountConditionQuery(get_called_class());
    }
}
