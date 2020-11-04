<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogProductAttributeSet]].
 *
 * @see CatalogProductAttributeSet
 */
class CatalogProductAttributeSetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogProductAttributeSet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogProductAttributeSet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
