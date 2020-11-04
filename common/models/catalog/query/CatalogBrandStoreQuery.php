<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogBrandStore]].
 *
 * @see CatalogBrandStore
 */
class CatalogBrandStoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogBrandStore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogBrandStore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
