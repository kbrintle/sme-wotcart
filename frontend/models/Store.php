<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $is_default
  */
class Store extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'created_at'], 'required'],
            [['created_at', 'updated_at', 'is_active', 'is_deleted', 'is_default'], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'is_default' => 'Is Default',
        ];
    }
}
