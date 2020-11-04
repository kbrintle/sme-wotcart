<?php

namespace common\models\core\query;
/**
 * This is the ActiveQuery class for [[CoreConfig]].
 *
 * @see CoreConfig
 */
class CoreConfigQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CoreConfig[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CoreConfig|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}