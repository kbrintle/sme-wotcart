<?php

namespace common\models\customer\search;

use Yii;

/**
 * This is the model class for table "customer_search".
 *
 * @property integer $customer_search_id
 * @property integer $store_id
 * @property integer $language_id
 * @property integer $customer_id
 * @property string $keyword
 * @property integer $category_id
 * @property integer $sub_category
 * @property integer $description
 * @property integer $products
 * @property string $ip
 * @property string $date_added
 */
class CustomerInputSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_search';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'language_id', 'customer_id', 'keyword', 'sub_category', 'description', 'products', 'ip', 'date_added'], 'required'],
            [['store_id', 'language_id', 'customer_id', 'category_id', 'sub_category', 'description', 'products'], 'integer'],
            [['date_added'], 'safe'],
            [['keyword'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_search_id' => 'Customer Search ID',
            'store_id' => 'Store ID',
            'language_id' => 'Language ID',
            'customer_id' => 'Customer ID',
            'keyword' => 'Keyword',
            'category_id' => 'Category ID',
            'sub_category' => 'Sub Category',
            'description' => 'Description',
            'products' => 'Products',
            'ip' => 'Ip',
            'date_added' => 'Date Added',
        ];
    }
}