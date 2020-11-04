<?php

namespace common\models\settings\query;

use common\components\CurrentStore;

/**
 * This is the ActiveQuery class for [[\common\models\settings\SettingsStore]].
 *
 * @see \common\models\settings\SettingsStore
 */
class SettingsStoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\settings\SettingsStore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\settings\SettingsStore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function store()
    {
        return $this->where(['store_id' => CurrentStore::getStoreId()]);
    }

    public function defaultStore()
    {
        return $this->where(['is_default' => true]);
    }
}