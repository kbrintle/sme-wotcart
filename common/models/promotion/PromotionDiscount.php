<?php

namespace common\models\promotion;

use common\components\CurrentStore;
use common\models\catalog\CatalogProduct;
use frontend\controllers\CartController;
use Yii;

/**
 * This is the model class for table "promotion_discount".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $promotion_id
 * @property string $label
 * @property integer $amount
 * @property string $type
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 */
class PromotionDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promotion_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'promotion_id', 'label', 'amount', 'type', 'created_at'], 'required'],
            [['store_id', 'promotion_id', 'amount', 'created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['label', 'type'], 'string', 'max' => 255],
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
            'label' => 'Label',
            'amount' => 'Discount Amount',
            'type' => 'Type',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Find currently active promotions based on product ID
     * and optionally coupon code
     */
    public static function productHasTargetedDiscount($product_id) {
        $conditions = PromotionDiscountCondition::find()
            ->where(['IN', 'discount_id', PromotionDiscount::find()
                ->select('id')
                ->where(['IN', 'store_id', [0, CurrentStore::getStoreId()]])
                ->andWhere(['IN', 'promotion_id', PromotionStorePromotion::find()
                    ->select('promotion_id')
                    ->where(['IN', 'store_id', [0, CurrentStore::getStoreId()]])
                ])
                ->andWhere(['IN', 'promotion_id', PromotionPromotion::find()
                    ->select('id')
                    ->where(['IN', 'store_id', [0, CurrentStore::getStoreId()]])
                    ->andWhere('starts_at <= UNIX_TIMESTAMP()')
                    ->andWhere(['OR', 'ends_at >= UNIX_TIMESTAMP()', 'ends_at IS NULL'])
                ])
            ])->all();

        if ($conditions) {
            foreach ($conditions as $index => $condition) {
                if ($index == 0)
                    $discount_id = $condition->discount_id;

                switch ($condition->key) {
                    case 'sku':
                        $result = CatalogProduct::getSku($product_id) == $condition->value;
                        break;
                    case 'brand':
                        $result = CatalogProduct::getBrand($product_id) == $condition->value;
                        break;
                    case 'attribute-set':
                        $result = CatalogProduct::getAttributeSet($product_id) == $condition->value;
                        break;
                    case 'coupon-code':
                        $code = CartController::getPromoCode();
                        if ($code) {
                            $result = $code == $condition->value;
                        } else {
                            return false;
                        }
                }
                if (isset($result))
                    $eligibility[$condition->condition][$condition->key][] = $result;
            }

            if (isset($eligibility)) {
                $discount = [];
                foreach ($eligibility['and'] as $condition => $results) {
                    if (in_array(false, $results)) {
                        if (isset($eligibility['or'][$condition])) { // Condition failed on AND but has OR
                            if (in_array(true, $eligibility['or'][$condition])) {
                                $discount[] = true; // Condition passed on OR
                            } else {
                                $discount[] = false; // Condition failed on OR
                            }
                        } else {
                            $discount[] = false; // Condition failed on AND with no OR
                        }
                    } else {
                        $discount[] = true; // Condition passed on AND
                    }
                }

                if (!in_array(false, $discount)) {
                    // Product IS ELIGIBLE for a discount
                    $promotionDiscount = PromotionDiscount::findOne($discount_id);
                    if ($promotionDiscount) {
                        return $promotionDiscount;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\query\PromotionDiscountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\promotion\query\PromotionDiscountQuery(get_called_class());
    }
}
