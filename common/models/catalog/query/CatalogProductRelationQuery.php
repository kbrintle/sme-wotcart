<?php

namespace common\models\catalog\query;

use common\components\CurrentStore;

/**
 * This is the ActiveQuery class for [[\common\models\catalog\models\CatalogProductRelation]].
 *
 * @see \common\models\catalog\models\CatalogProductRelation
 */
class CatalogProductRelationQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\catalog\models\CatalogProductRelation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\catalog\models\CatalogProductRelation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function store()
    {
        if(!CurrentStore::getStoreId()){
            return $this;
        }else{
            return $this->where(['store_id' => CurrentStore::getStoreId()]);
        }
    }
}