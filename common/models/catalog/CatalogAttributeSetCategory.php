<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogAttributeSetCategoryQuery;
use Yii;

/**
 * This is the model class for table "catalog_attribute_set_category".
 *
 * @property integer $id
 * @property string $label
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogAttributeSet $catalogAttributeSet
 */
class CatalogAttributeSetCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_set_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'created_at'], 'required'],
            [['created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeSet()
    {
        return $this->hasOne(CatalogAttributeSet::className(), ['id' => 'id']);
    }

    public static function find()
    {
        return new CatalogAttributeSetCategoryQuery(get_called_class());
    }
}
