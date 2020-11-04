<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogCategoryProduct]].
 *
 * @see CatalogCategoryProduct
 */
class CatalogCategoryProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogCategoryProduct[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogCategoryProduct|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
