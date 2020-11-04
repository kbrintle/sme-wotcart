<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ImageManager".
 *
 * @property integer $id
 * @property string $fileName
 * @property string $fileHash
 * @property string $created
 * @property string $modified
 */
class ImageManager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ImageManager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'modified'], 'required'],
            [['created', 'modified'], 'safe'],
            [['fileName'], 'string', 'max' => 128],
            [['fileHash'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fileName' => 'File Name',
            'fileHash' => 'File Hash',
            'created' => 'Created',
            'modified' => 'Modified',
        ];
    }
}
