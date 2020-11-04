<?php

namespace common\models\sales;

use Yii;

/**
 * This is the model class for table "sales_order_status".
 *
 * @property integer $order_status_id
 * @property integer $language_id
 * @property string $name
 */
class SalesOrderStatus extends \yii\db\ActiveRecord
{
    const PENDING = 10; // Pending Status ID;
    const ACCEPTED = 1; // Accepted Status ID;
    const DELIVERED = 13; // Delivered Status ID;
    const ON_HOLD = 6; // On Hold Status ID;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'name'], 'required'],
            [['language_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_status_id' => 'Order Status ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}