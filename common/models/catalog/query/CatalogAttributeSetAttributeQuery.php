<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttributeSetAttribute]].
 *
 * @see CatalogAttributeSetAttribute
 */
class CatalogAttributeSetAttributeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeSetAttribute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeSetAttribute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
