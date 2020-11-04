<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttributeOption]].
 *
 * @see CatalogAttributeOption
 */
class CatalogAttributeOptionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeOption[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeOption|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
