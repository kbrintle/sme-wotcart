<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesOrderAddress]].
 *
 * @see SalesOrderAddress
 */
class SalesOrderAddressQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesOrderAddress[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesOrderAddress|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}