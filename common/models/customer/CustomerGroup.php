<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_group".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $approval
 * @property integer $sort_order
 */
class CustomerGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval', 'sort_order'], 'required'],
            [['approval', 'sort_order'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Customer Group ID',
            'name' => 'Group',
            'description' => 'Description',
            'approval' => 'Approval',
            'sort_order' => 'Sort Order',
        ];
    }
}
