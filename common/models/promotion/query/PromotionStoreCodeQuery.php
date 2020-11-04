<?php

namespace common\models\promotion\query;

/**
 * This is the ActiveQuery class for [[PromotionStoreCode]].
 *
 * @see PromotionStoreCode
 */
class PromotionStoreCodeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PromotionStoreCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PromotionStoreCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
