<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogAttributeTypeQuery;
use Yii;

/**
 * This is the model class for table "catalog_attribute_type".
 *
 * @property integer $id
 * @property string $type
 * @property string $format
 * @property string $size
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogAttribute[] $catalogAttributes
 */
class CatalogAttributeType extends \yii\db\ActiveRecord
{
    const TEXT        = 1;
    const SELECT      = 2;
    const NUMBER      = 3;
    const IMAGE       = 4;
    const DATE        = 5;
    const FILE        = 6;
    const TEL         = 7;
    const URL         = 8;
    const TEXTAREA    = 9;
    const MULTISELECT = 10;
    const BOOLEAN     = 11;
    const DATETIME    = 12;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'format', 'created_at'], 'required'],
            [['created_at', 'modified_at', 'is_active', 'is_deleted'], 'integer'],
            [['type', 'format', 'size'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'type'        => 'Type',
            'format'      => 'Format',
            'size'        => 'Size',
            'created_at'  => 'Created At',
            'modified_at' => 'Modified At',
            'is_active'   => 'Is Active',
            'is_deleted'  => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributes()
    {
        return $this->hasMany(CatalogAttribute::className(), ['type_id' => 'id']);
    }

    public static function find()
    {
        return new CatalogAttributeTypeQuery(get_called_class());
    }
}
