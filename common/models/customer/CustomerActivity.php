<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_activity".
 *
 * @property integer $customer_activity_id
 * @property integer $customer_id
 * @property string $key
 * @property string $data
 * @property string $ip
 * @property integer $created_at
 */
class CustomerActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'key', 'data', 'ip', 'created_at'], 'required'],
            [['customer_id', 'created_at'], 'integer'],
            [['data'], 'string'],
            [['created_at'], 'safe'],
            [['key'], 'string', 'max' => 64],
            [['ip'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_activity_id' => 'Customer Activity ID',
            'customer_id' => 'Customer ID',
            'key' => 'Key',
            'data' => 'Data',
            'ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }

    public function loginActivity($id)
    {
        $activity = new self();
        $activity->customer_id = $id;
        $activity->data = "login";
        $activity->key = "1";
        $activity->ip = Yii::$app->getRequest()->getUserIP();
        $activity->created_at = time();
        $activity->save(true);
    }

    public function logoutActivity()
    {
        $activity = new self();
        $activity->customer_id = Yii::$app->user->getId();
        $activity->data = "logout";
        $activity->key = "1";
        $activity->ip = Yii::$app->getRequest()->getUserIP();
        $activity->created_at = time();
        $activity->save(true);
    }
}
