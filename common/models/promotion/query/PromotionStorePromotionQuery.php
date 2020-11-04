<?php

namespace common\models\promotion\query;

/**
 * This is the ActiveQuery class for [[\common\models\promotion\PromotionStorePromotion]].
 *
 * @see \common\models\promotion\PromotionStorePromotion
 */
class PromotionStorePromotionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionStorePromotion[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\promotion\PromotionStorePromotion|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
