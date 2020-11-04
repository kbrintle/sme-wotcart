<?php

namespace common\models\sales\query;

use common\components\CurrentStore;

/**
 * This is the ActiveQuery class for [[SalesOrder]].
 *
 * @see SalesOrder
 */
class SalesOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SalesOrder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SalesOrder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function active()
    {
       return $this->andWhere('[[status]]=1');
    }

    public function store()
    {
        if(!CurrentStore::getStoreId()){
            return $this;
        }else{
            return $this->where(['store_id' => CurrentStore::getStoreId()]);
        }

    }
}