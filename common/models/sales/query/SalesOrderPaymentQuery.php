<?php

namespace common\models\sales\query;

/**
 * This is the ActiveQuery class for [[SalesOrderPayment]].
 *
 * @see SalesOrderPayment
 */
class SalesOrderPaymentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesOrderPayment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesOrderPayment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}