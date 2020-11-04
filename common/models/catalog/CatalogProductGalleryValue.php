<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product_gallery_value".
 *
 * @property int $gallery_id Value ID
 * @property int $store_id Store ID
 * @property string $label Label
 * @property int $sort Sort
 * @property int $is_active Is Active
 * @property int $is_default Is Default
 */
class CatalogProductGalleryValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_gallery_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gallery_id', 'store_id'], 'required'],
            [['gallery_id', 'store_id', 'sort', 'is_active', 'is_default'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['gallery_id', 'store_id'], 'unique', 'targetAttribute' => ['gallery_id', 'store_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gallery_id' => 'Gallery ID',
            'store_id' => 'Store ID',
            'label' => 'Label',
            'sort' => 'Sort',
            'is_active' => 'Is Active',
            'is_default' => 'Is Default'
        ];
    }
}