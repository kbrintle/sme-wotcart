<?php

namespace common\models\promotion\query;

use common\models\promotion\PromoImages;
use common\components\CurrentStore;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[PromoImages]].
 *
 * @see PromoImages
 */
class PromoImagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PromoImages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PromoImages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function mine($condition = 0){
        $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : NULL;
        $query    = $this->andWhere(['store_id' => $store_id]);

        if ($condition)
            $query = $query->andFilterWhere($condition);

        return $query->orderBy('order');
    }

    public function library($condition = 0){
        $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : NULL;
        $children = [];

        if ($store_id !== NULL) {
            $query = $this->where(['store_id' => CurrentStore::getStoreId()]);
            $query = $query->orWhere(['store_id' => NULL]);

            $children = PromoImages::find()
                ->select('parent_id')
                ->where([
                    'store_id' => CurrentStore::getStoreId(),
                ])
                ->andWhere(['NOT', 'parent_id IS NULL'])
                ->all();
            $children = $children ? ArrayHelper::getColumn($children, 'parent_id') : $children;
        } else {
            $query = $this->where(['store_id' => NULL]);
        }

        $query = $query->andWhere(['NOT IN', 'id', $children]);

        if ($condition)
            $query = $query->andFilterWhere($condition);

        return $query;
    }

    //@NOTE: this will replace the ->all() method
    public function homepage(){
        $output = PromoImages::find()->mine(['active' => true])->all();

        if( count($output) < 4 ){
            $default_promos = PromoImages::find()
                                ->where([
                                    'store_id' => 1,
                                    'active'   => true
                                ])
                                ->limit( (4 - count($output)) )
                                ->orderBy('order')
                                ->all();
            $output = array_merge($output, $default_promos);
        }

        return $output;
    }
}
