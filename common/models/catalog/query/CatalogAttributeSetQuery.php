<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttributeSet]].
 *
 * @see CatalogAttributeSet
 */
class CatalogAttributeSetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeSet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeSet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
