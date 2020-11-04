<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesOrderHistory]].
 *
 * @see SalesOrderHistory
 */
class SalesOrderHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesOrderHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesOrderHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}