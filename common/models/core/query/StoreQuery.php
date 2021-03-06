<?php

namespace common\models\core\query;

/**
 * This is the ActiveQuery class for [[Store]].
 *
 * @see Store
 */
class StoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Store[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Store|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}