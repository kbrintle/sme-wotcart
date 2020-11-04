<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "settings_seo".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $ga_code
 * @property string $page_title
 * @property string $page_title_prefix
 * @property string $page_title_suffix
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $created_time
 */
class SettingsSeo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'created_at'], 'integer'],
            [['meta_description'], 'string'],
            [['page_title', 'page_title_prefix', 'page_title_suffix','ga_code', 'meta_keywords'], 'string', 'max' => 255],
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
            'page_title' => 'Page Title',
            'page_title_prefix' => 'Page Title Prefix',
            'page_title_suffix' => 'Page Title Suffix',
            'ga_code' => 'Ga Code',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'created_time' => 'Created Time',
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\settings\query\SettingsSeoQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {
        $query = new \common\models\settings\query\SettingsSeoQuery(get_called_class());

        if( ! $overRideScope )
            $query->store();

        return $query;
    }
}