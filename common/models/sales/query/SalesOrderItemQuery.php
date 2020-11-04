<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesOrderItem]].
 *
 * @see SalesOrderItem
 */
class SalesOrderItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesOrderItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesOrderItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}