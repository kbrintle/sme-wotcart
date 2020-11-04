<?php

namespace common\models\core\query;

/**
 * This is the ActiveQuery class for [[Programs]].
 *
 * @see Programs
 */
class RegionsQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Programs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Programs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function abc()
    {
        return $this->orderBy(['country' => SORT_ASC]);
    }
}
