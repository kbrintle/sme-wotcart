<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_online".
 *
 * @property string $ip
 * @property integer $customer_id
 * @property string $url
 * @property string $referer
 * @property string $date_added
 */
class CustomerOnline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_online';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'customer_id', 'url', 'referer', 'date_added'], 'required'],
            [['customer_id'], 'integer'],
            [['url', 'referer'], 'string'],
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
            'ip' => 'Ip',
            'customer_id' => 'Customer ID',
            'url' => 'Url',
            'referer' => 'Referer',
            'date_added' => 'Date Added',
        ];
    }
}
