<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_transaction".
 *
 * @property integer $customer_transaction_id
 * @property integer $customer_id
 * @property integer $order_id
 * @property string $description
 * @property string $amount
 * @property string $date_added
 */
class CustomerTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'description', 'amount', 'date_added'], 'required'],
            [['customer_id', 'order_id'], 'integer'],
            [['description'], 'string'],
            [['amount'], 'number'],
            [['date_added'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_transaction_id' => 'Customer Transaction ID',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'description' => 'Description',
            'amount' => 'Amount',
            'date_added' => 'Date Added',
        ];
    }
}