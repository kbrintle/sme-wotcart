<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttributeSetCategory]].
 *
 * @see CatalogAttributeSetCategory
 */
class CatalogAttributeSetCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeSetCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeSetCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
