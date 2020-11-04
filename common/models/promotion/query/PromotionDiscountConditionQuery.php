<?php

namespace common\models\promotion\query;

/**
 * This is the ActiveQuery class for [[\common\models\promotion\PromotionDiscountCondition]].
 *
 * @see \common\models\promotion\PromotionDiscountCondition
 */
class PromotionDiscountConditionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionDiscountCondition[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionDiscountCondition|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
