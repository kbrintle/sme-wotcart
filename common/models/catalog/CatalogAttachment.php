<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogAttachmentQuery;

/**
 * This is the model class for table "catalog_attachment".
 *
 * @property int $attachment_id Attachment ID
 * @property string $store_id Store ID
 * @property string $title Title
 * @property string $file_name File Name
 * @property string $file_type File Type
 * @property int $is_active Is Active
 * @property int $is_deleted Is Deleted
 * @property int $created_at Created At
 * @property int $updated_at Modified At
 *
 */
class CatalogAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'required'],
            [['is_active'], 'boolean'],
            [['file_name', 'title', 'file_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'Attachment ID',
            'title'   => 'Title',
            'store_id' => 'Store ID',
            'file_name' => 'File Name',
            'file_type' => 'File Type',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
        ];
    }

    public static function find()
    {
        return new CatalogAttachmentQuery(get_called_class());
    }

    public function getCatalogProductAttachment()
    {
        return $this->hasMany(CatalogProductAttachment::className(), ['attachment_id' => 'attachment_id']);
    }
}
