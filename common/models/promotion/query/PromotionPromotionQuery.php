<?php

namespace common\models\promotion\query;

/**
 * This is the ActiveQuery class for [[\common\models\promotion\PromotionPromotion]].
 *
 * @see \common\models\promotion\PromotionPromotion
 */
class PromotionPromotionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionPromotion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionPromotion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
