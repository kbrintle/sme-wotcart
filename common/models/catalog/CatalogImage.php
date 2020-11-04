<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $id
 * @property string $file_name
 * @property integer $created_at
 * @property integer $modified_at
 */
class CatalogImage extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'modified_at'], 'integer'],
            [['file_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }


}