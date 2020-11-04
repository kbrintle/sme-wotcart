<?php

namespace common\models\store\query;

use common\components\CurrentStore;
/**
 * This is the ActiveQuery class for [[Location]].
 *
 * @see Location
 */
class StoreLocationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    public function store()
    {
        return $this->where(['store_id' => CurrentStore::getStoreId()]);
    }

    /**
     * @inheritdoc
     * @return Location[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Location|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}