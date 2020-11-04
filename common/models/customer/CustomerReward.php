<?php

namespace common\models\customer;

use common\models\sales\SalesOrder;
use common\models\store\StoreNewsletterSubscriber;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\customer\query\CustomerQuery;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $order_id
 * @property string $comments
 * @property number $points
 * @property string $type
 * @property integer $created_at
 */
class CustomerReward extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_reward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'points', 'type', 'created_at'], 'required'],
            [['type', 'order_id', 'comments'], 'string'],
            [['customer_id', 'created_at'], 'integer'],
            [['points'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'points' => 'Points',
            'type' => 'Type',
            'created_at' => 'Created At'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function addPoints($model)
    {
        $addReward = new self();
        $addReward->order_id = $model->getSalesOrder();
        $addReward->customer_id = $model->customer_user_id;
        $addReward->points = $model->getSubTotal();
        $addReward->type = "add";
        $addReward->created_at = time();
        $addReward->save(false);
    }

    /**
     * @inheritdoc
     */
    public static function subtractPoints($model)
    {
        if (isset($model->order["discount_reward_amount"])) {
            $subReward = new self();
            $subReward->order_id = $model->getSalesOrder();
            $subReward->customer_id = $model->customer_user_id;
            $subReward->points = 0 - abs($model->order["discount_reward_amount"] * 100);
            $subReward->type = "sub";
            $subReward->created_at = time();
            $subReward->save(false);
        }
    }


    /**
     * @inheritdoc
     */
    public static function getUsablePoints($customer_id)
    {
        $rewards = self::find()->Where(['customer_id' => $customer_id])->sum('points');
        if ($rewards)
            return floor($rewards);
        else return 0;
    }

    public function getOrder(){
        return $this->hasOne(SalesOrder::className(), ['id' => 'order_id']);

    }
}