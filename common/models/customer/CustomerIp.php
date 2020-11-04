<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_ip".
 *
 * @property integer $customer_ip_id
 * @property integer $customer_id
 * @property string $ip
 * @property string $date_added
 */
class CustomerIp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_ip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'ip', 'date_added'], 'required'],
            [['customer_id'], 'integer'],
            [['date_added'], 'safe'],
            [['ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_ip_id' => 'Customer Ip ID',
            'customer_id' => 'Customer ID',
            'ip' => 'Ip',
            'date_added' => 'Date Added',
        ];
    }
}
