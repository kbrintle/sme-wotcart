<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "core_url_rewrite".
 *
 * @property integer $url_rewrite_id
 * @property integer $store_id
 * @property integer $category_id
 * @property integer $product_id
 * @property string $id_path
 * @property string $request_path
 * @property string $target_path
 * @property integer $is_system
 * @property string $options
 * @property string $description
 */
class CoreUrlRewrite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'core_url_rewrite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'category_id', 'product_id', 'is_system'], 'integer'],
            [['id_path', 'request_path', 'target_path', 'options', 'description'], 'string', 'max' => 255],
            [['request_path', 'store_id'], 'unique', 'targetAttribute' => ['request_path', 'store_id'], 'message' => 'The combination of Store ID and Request Path has already been taken.'],
            [['id_path', 'is_system', 'store_id'], 'unique', 'targetAttribute' => ['id_path', 'is_system', 'store_id'], 'message' => 'The combination of Store ID, Id Path and Is System has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url_rewrite_id' => 'Url Rewrite ID',
            'store_id' => 'Store ID',
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'id_path' => 'Id Path',
            'request_path' => 'Request Path',
            'target_path' => 'Target Path',
            'is_system' => 'Is System',
            'options' => 'Options',
            'description' => 'Description',
        ];
    }
}