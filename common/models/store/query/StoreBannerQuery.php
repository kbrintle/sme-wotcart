<?php

namespace common\models\store\query;

/**
 * This is the ActiveQuery class for [[StoreBanner]].
 *
 * @see StoreBanner
 */
class StoreBannerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return StoreBanner[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return StoreBanner|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}