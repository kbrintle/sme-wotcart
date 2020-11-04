<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "admin_store".
 *
 * @property integer $admin_id
 * @property integer $store_id
 */
class AdminStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'admin_id' => 'Admin ID',
            'store_id' => 'Store ID',
        ];
    }
}