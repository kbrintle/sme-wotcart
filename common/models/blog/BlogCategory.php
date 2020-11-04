<?php

namespace common\models\blog;

use Yii;

/**
 * This is the model class for table "blog_cat".
 *
 * @property integer $cat_id
 * @property string $title
 * @property string $identifier
 * @property integer $sort_order
 * @property string $meta_keywords
 * @property string $meta_description
 */
class BlogCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_cat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order', 'meta_keywords', 'meta_description'], 'required'],
            [['sort_order'], 'integer'],
            [['meta_keywords', 'meta_description'], 'string'],
            [['title', 'identifier'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'title' => 'Title',
            'identifier' => 'Identifier',
            'sort_order' => 'Sort Order',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
        ];
    }

    /**
     * @inheritdoc
     * @return BlogCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlogCategoryQuery(get_called_class());
    }
}
