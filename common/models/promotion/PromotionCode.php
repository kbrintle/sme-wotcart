<?php

namespace common\models\promotion;

use Yii;
use common\models\promotion\query\PromotionCodeQuery;

/**
 * This is the model class for table "promotion_code".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property string $amount
 * @property string $event
 * @property string $starts_at
 * @property string $ends_at
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionCode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'type', 'event', 'starts_at', 'ends_at', 'created_at'], 'required'],
            [['created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['code', 'type', 'amount', 'event', 'starts_at', 'ends_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'type' => 'Type',
            'amount' => 'Amount',
            'event' => 'Can Be Used',
            'starts_at' => 'Starts At',
            'ends_at' => 'Ends At',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @inheritdoc
     * @return PromotionCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PromotionCodeQuery(get_called_class());
    }
}
