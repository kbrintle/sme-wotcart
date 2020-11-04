<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesQuote]].
 *
 * @see SalesQuote
 */
class SalesQuoteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesQuote[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesQuote|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}