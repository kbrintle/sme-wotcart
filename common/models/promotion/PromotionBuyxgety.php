<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "promotion_buyxgety".
 *
 * @property int $id
 * @property int $promotion_id
 * @property string $label
 * @property string $x_sku
 * @property string $x_amount
 * @property string $y_sku
 * @property string $y_amount
 * @property int $auto_add
 * @property int $created_at
 * @property int $modified_at
 * @property int $is_active
 * @property int $is_deleted
 */
class PromotionBuyxgety extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_buyxgety';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'created_at'], 'required'],
            [['promotion_id', 'auto_add', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'x_amount', 'y_amount'], 'integer'],
            [['x_sku', 'y_sku', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promotion_id' => 'Promotion ID',
            'label' => "Label",
            'x_sku' => 'of sku',
            'x_amount' => 'Buy',
            'y_sku' => 'of sku',
            'y_amount' => 'get',
            'auto_add' => 'Auto Add',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }
}