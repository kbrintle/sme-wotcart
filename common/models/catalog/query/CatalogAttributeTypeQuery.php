<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttributeType]].
 *
 * @see CatalogAttributeType
 */
class CatalogAttributeTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
