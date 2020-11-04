<?php

namespace common\models\promotion;

use Yii;

/**
 * This is the model class for table "promotion_free_product".
 *
 * @property int $id
 * @property int $promotion_id
 * @property int $product_id
 * @property int $sort
 */
class PromotionFreeProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotion_free_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promotion_id', 'product_id'], 'required'],
            [['promotion_id', 'product_id', 'sort'], 'integer'],
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
            'product_id' => 'Product ID',
            'sort' => 'Sort'
        ];
    }
}
