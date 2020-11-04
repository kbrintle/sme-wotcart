<?php

namespace common\models\promotion;

use Yii;
Use common\models\core\Store;

/**
 * This is the model class for table "promotion_promotion".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $label
 * @property integer $starts_at
 * @property integer $ends_at
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionPromotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_promotion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'starts_at'], 'required'],
            [['store_id', 'starts_at', 'ends_at', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['label'], 'string', 'max' => 255],
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
            'label' => 'Label',
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
     * @return \common\models\promotion\query\PromotionPromotionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\promotion\query\PromotionPromotionQuery(get_called_class());
    }

    public function getStore(){
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
