<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_wishlist".
 *
 * @property integer $customer_id
 * @property integer $product_id
 * @property string $date_added
 */
class CustomerWishlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_wishlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'product_id', 'date_added'], 'required'],
            [['customer_id', 'product_id'], 'integer'],
            [['date_added'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'product_id' => 'Product ID',
            'date_added' => 'Date Added',
        ];
    }
}