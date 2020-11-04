<?php

namespace common\models\catalog\query;


/**
 * This is the ActiveQuery class for [[CatalogBrand]].
 *
 * @see CatalogBrand
 */
class CatalogBrandQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogBrand[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogBrand|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function isActive(){
        return $this->andWhere([
            'is_active'     => true,
            'is_deleted'    => false
        ]);
    }

}
