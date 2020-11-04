<?php

namespace common\models\cms\query;
use common\components\CurrentStore;
use common\models\cms\CmsPage;

/**
 * This is the ActiveQuery class for [[CmsPage]].
 *
 * @see CmsPage
 */
class CmsPageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CmsPage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CmsPage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function byKey($url_key=null){
        if($url_key){
            $store_id = CurrentStore::getStoreId();

            $this->leftJoin('cms_page_store', 'cms_page_store.page_id = cms_page.id');
            $this->andFilterWhere([
                'cms_page_store.store_id' => [0, $store_id],
                'url_key'   => $url_key
            ]);
            $this->orderBy('cms_page_store.store_id');
        }
        return $this;
    }
}
