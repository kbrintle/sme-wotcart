<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogAttributeSetQuery;
use Yii;

/**
 * This is the model class for table "catalog_attribute_set".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $label
 * @property string $slug
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $is_default
 *
 * @property CatalogAttributeSetAttribute[] $catalogAttributeSetAttributes
 * @property CatalogProductAttributeSet[] $catalogProductAttributeSets
 */
class CatalogAttributeSet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_set';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'is_default'], 'integer'],
            [['label', 'created_at'], 'required'],
            [['label', 'slug'], 'string', 'max' => 255],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttributeSetCategory::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'label' => 'Label',
            'slug' => 'Slug',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'is_default' => 'Is Default',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeSetAttributes()
    {
        return $this->hasMany(CatalogAttributeSetAttribute::className(), ['set_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProductAttributeSets()
    {
        return $this->hasMany(CatalogProductAttributeSet::className(), ['set_id' => 'id']);
    }

    public static function find()
    {
        return new CatalogAttributeSetQuery(get_called_class());
    }
}
