<?php

namespace common\models\catalog;

/**
 * This is the model class for table "catalog_product_attachment".
 *
 * @property int $id ID
 * @property int $product_id Product ID
 * @property int $attachment_id Attachment ID
 *
 */
class CatalogProductAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','product_id','attachment_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'attachment_id' => 'Attachment ID'
        ];
    }

    public static function getAttachments($product_id = null){
        if($product_id == null){
            throwException();
        }

        $attachments = CatalogAttachment::find()
            ->leftJoin('catalog_product_attachment', 'catalog_product_attachment.attachment_id = catalog_attachment.attachment_id')
            ->where([
            'product_id' => $product_id
        ])->all();


        return $attachments;
    }
}
