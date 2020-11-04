<?php

namespace common\models\catalog\query;

/**
 * This is the ActiveQuery class for [[CatalogAttribute]].
 *
 * @see CatalogAttribute
 */
class CatalogAttachmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttribute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttribute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function isFilterable(){
        return $this->andWhere([
            'is_filterable' => true
        ]);
    }
}