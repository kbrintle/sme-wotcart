<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "sales_order_history".
 *
 * @property integer $order_history_id
 * @property integer $order_id
 * @property integer $order_status_id
 * @property integer $notify
 * @property string $comment
 * @property string $date_added
 */
class SalesOrderHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_status_id', 'comment', 'date_added'], 'required'],
            [['order_id', 'order_status_id', 'notify'], 'integer'],
            [['comment'], 'string'],
            [['date_added'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_history_id' => 'Order History ID',
            'order_id' => 'Order ID',
            'order_status_id' => 'Order Status ID',
            'notify' => 'Notify',
            'comment' => 'Comment',
            'date_added' => 'Date Added',
        ];
    }

    /**
     * @inheritdoc
     * @return SalesOrderHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesOrderHistoryQuery(get_called_class());
    }
}
