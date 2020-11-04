<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "sales_representative".
 *
 * @property int $id
 * @property string $initials
 * @property string $first_name
 * @property string $last_name
 */
class SalesRepresentative extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_representative';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['initials'], 'string', 'max' => 5],
            [['first_name', 'last_name'], 'string', 'max' => 55],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'initials' => 'Initials',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];
    }
}