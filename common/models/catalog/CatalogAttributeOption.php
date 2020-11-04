<?php

namespace common\models\catalog;

use Yii;
use common\models\core\Store;
use common\models\catalog\query\CatalogAttributeOptionQuery;

/**
 * This is the model class for table "catalog_attribute_option".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $store_id
 * @property string $value
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $order
 *
 * @property CatalogAttribute $id0
 * @property Store $id1
 */
class CatalogAttributeOption extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'store_id', 'value', 'created_at'], 'required'],
            [['attribute_id', 'store_id', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'order'], 'integer'],
            [['value'], 'string'],
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
            'attribute_id' => 'Attribute ID',
            'store_id' => 'Store ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(CatalogAttribute::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId1()
    {
        return $this->hasOne(Store::className(), ['id' => 'id']);
    }

    public static function find()
    {
        return new CatalogAttributeOptionQuery(get_called_class());
    }
}
