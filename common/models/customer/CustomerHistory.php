<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "customer_history".
 *
 * @property integer $customer_history_id
 * @property integer $customer_id
 * @property string $comment
 * @property string $date_added
 */
class CustomerHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'comment', 'date_added'], 'required'],
            [['customer_id'], 'integer'],
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
            'customer_history_id' => 'Customer History ID',
            'customer_id' => 'Customer ID',
            'comment' => 'Comment',
            'date_added' => 'Date Added',
        ];
    }
}