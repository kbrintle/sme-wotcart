<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogProductFeature]].
 *
 * @see CatalogProductFeature
 */
class CatalogFeatureQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogProductFeature[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogProductFeature|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
