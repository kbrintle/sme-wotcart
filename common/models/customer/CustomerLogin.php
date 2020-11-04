<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_login".
 *
 * @property integer $customer_login_id
 * @property string $email
 * @property string $ip
 * @property integer $total
 * @property string $date_added
 * @property string $date_modified
 */
class CustomerLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'ip', 'total', 'date_added', 'date_modified'], 'required'],
            [['total'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['email'], 'string', 'max' => 96],
            [['ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_login_id' => 'Customer Login ID',
            'email' => 'Email',
            'ip' => 'Ip',
            'total' => 'Total',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}