<?php

namespace common\models\promotion\query;

/**
 * This is the ActiveQuery class for [[PromotionCode]].
 *
 * @see PromotionCode
 */
class PromotionCodeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PromotionCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PromotionCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
