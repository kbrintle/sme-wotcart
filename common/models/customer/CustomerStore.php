<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "category_store".
 *
 * @property integer $customer_id
 * @property integer $store_id
 */
class CustomerStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'store_id'], 'required'],
            [['customer_id', 'store_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'store_id' => 'Store ID',
        ];
    }
}
