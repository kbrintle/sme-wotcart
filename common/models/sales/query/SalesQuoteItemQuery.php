<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesQuoteItem]].
 *
 * @see SalesQuoteItem
 */
class SalesQuoteItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesQuoteItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesQuoteItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
