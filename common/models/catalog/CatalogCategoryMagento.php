<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_category_magento".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 */
class CatalogCategoryMagento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category_magento';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 155],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }
}