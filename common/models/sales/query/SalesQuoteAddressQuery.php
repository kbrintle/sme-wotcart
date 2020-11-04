<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesQuoteAddress]].
 *
 * @see SalesQuoteAddress
 */
class SalesQuoteAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesQuoteAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesQuoteAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
