<?php

namespace common\models\catalog;

/**
 * This is the model class for table "catalog_product_relation".
 *
 * @property integer $id
 * @property string $type_name
 */
class CatalogProductRelationType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'catalog_product_relation_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => 'Type Name'
        ];
    }
}