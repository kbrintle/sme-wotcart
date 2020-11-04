<?php

namespace common\models\catalog\query;
use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;

/**
 * This is the ActiveQuery class for [[CatalogAttributeValue]].
 *
 * @see CatalogAttributeValue
 */
class CatalogAttributeValueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogAttributeValue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogAttributeValue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }



    public function byName($attribute, $product_id){
        $catalog_attribute = CatalogAttribute::find()
            ->where([
                'label' => $attribute
            ])
            ->select('id');

        return $this->andWhere([
            'store_id'      => [CurrentStore::getStoreId(), '0'],
            'attribute_id'  => $catalog_attribute,
            'product_id'    => $product_id
        ]);
    }

    public function bySlug($slug, $product_id) {
        $catalog_attribute = CatalogAttribute::find()
            ->where([
                'slug' => strtolower($slug)
            ])
            ->select('id');

        return $this->andWhere([
            'store_id'      => [CurrentStore::getStoreId(), '0'],
            'attribute_id'  => $catalog_attribute,
            'product_id'    => $product_id
        ]);
    }
}
