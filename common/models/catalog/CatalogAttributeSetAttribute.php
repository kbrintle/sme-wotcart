<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogAttributeSetAttributeQuery;
use Yii;

/**
 * This is the model class for table "catalog_attribute_set_attribute".
 *
 * @property integer $id
 * @property integer $set_id
 * @property integer $attribute_id
 * @property integer $set_category_id
 * @property integer $is_pivot
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_editable
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogAttributeSet $set
 * @property CatalogAttribute $id0
 */
class CatalogAttributeSetAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_set_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['set_id', 'attribute_id', 'set_category_id', 'created_at'], 'required'],
            [['set_id', 'attribute_id', 'set_category_id', 'is_pivot', 'created_at', 'modified_at', 'is_editable', 'is_active', 'is_deleted'], 'integer'],
            [['set_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttributeSet::className(), 'targetAttribute' => ['set_id' => 'id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttribute::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'set_id' => 'Set ID',
            'attribute_id' => 'Attribute ID',
            'set_category_id' => 'Set Category ID',
            'is_pivot' => 'Is Pivot',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_editable' => 'Is Editable',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSet()
    {
        return $this->hasOne(CatalogAttributeSet::className(), ['id' => 'set_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(CatalogAttribute::className(), ['id' => 'id']);
    }

    public static function find()
    {
        return new CatalogAttributeSetAttributeQuery(get_called_class());
    }
}
