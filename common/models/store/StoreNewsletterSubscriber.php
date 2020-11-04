<?php

namespace common\models\store;

use Yii;
use common\components\CurrentStore;
use frontend\components\CurrentCustomer;
use common\models\store\query\StoreNewsletterSubscriberQuery;
use common\models\core\Store;

/**
 * This is the model class for table "newsletter_subscriber".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $change_status_at
 * @property integer $customer_id
 * @property string $email
 * @property integer $is_active
 * @property string $confirm_code
 * @property integer $created_time
 */
class StoreNewsletterSubscriber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_newsletter_subscriber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'customer_id', 'is_active', 'created_time'], 'integer'],
            [['change_status_at'], 'safe'],
            [['email'], 'string', 'max' => 150],
            ['email', 'unique', 'targetClass' => '\frontend\models\User', 'message' => 'This email address has already been taken.'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'change_status_at' => 'Change Status At',
            'customer_id' => 'Customer ID',
            'email' => 'Email',
            'is_active' => 'Is Active',
            'confirm_code' => 'Confirm Code',
            'created_time' => 'Created Time',
        ];
    }

    /**
     * @inheritdoc
     * @return NewsletterSubscriberQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreNewsletterSubscriberQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * @return NewsletterSubscriberQuery the active query used by this AR class.
     */
    public static function subscribe()
    {
        $subscriber = new StoreNewsletterSubscriber;
        $subscriber->store_id    = CurrentStore::getStoreId();
        $subscriber->customer_id = CurrentCustomer::getCustomer()->id;
        $subscriber->email = CurrentCustomer::getCustomer()->email;
        $subscriber->save(false);

        return;
    }

    public function getStore(){
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @inheritdoc
     * @return NewsletterSubscriberQuery the active query used by this AR class.
     */
    public static function unsubscribe()
    {
        StoreNewsletterSubscriber::find()
            ->where(['customer_id'=>CurrentCustomer::getCustomer()->id])
            ->one()
            ->delete();

        return;
    }
}