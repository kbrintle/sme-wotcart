<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cms_page_store".
 *
 * @property integer $store_id
 * @property integer $page_id
 */
class CmsPageStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_page_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'page_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'page_id' => 'Page ID',
        ];
    }
}