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
use \common\models\core\User;
use frontend\components\CurrentCustomer;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $store_id
 * @property string $legacy_group
 * @property string $clinic_name
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $first_name
 * @property string $last_name
 * @property string $time_zone
 * @property string $sales_rep
 * @property integer $local_store
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $last_login
 */
class Customer extends ActiveRecord implements IdentityInterface
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'password', 'is_active'], 'required'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['clinic_name', 'first_name', 'last_name', 'legacy_group', 'password', 'sales_rep'], 'string'],
            [['created_at', 'is_active', 'last_login', 'time_zone', 'store_id'], 'integer']
        ];
    }

    public function uniqueEmail($attribute) //this is a fix for the unique email validator not working
    {
        if (Customer::find()->where(["email" => $this->email])->count() > 0) {
            $this->addError($attribute, 'This email address has already been taken.');
            return false;
        }
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_active' => true]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['email' => $username, 'is_active' => true])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }


        return static::findOne([
            'access_token' => $token,
            'is_active' => true,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return ($timestamp + $expire >= time()) ? 1 : 0;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->updated_at = time();
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->access_token = null;
    }

    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find($overRideScope = false)
    {
        $preset_override = Yii::$app->session->get('override_scope');
        $overRideScope = $preset_override ? $preset_override : $overRideScope;

        $query = new CustomerQuery(get_called_class());

        if (!$overRideScope)
            $query->store();

        return $query;
    }

    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getAddress()
    {
        return $this->hasOne(CustomerAddress::className(), ['customer_id' => 'id']);
    }

    public function getOrders()
    {
        return $this->hasMany(SalesOrder::className(), ['customer_id' => 'id']);
    }

    public function getGroup()
    {
        return $this->hasOne(CustomerGroup::className(), ['id' => 'group_id']);
    }

    public function getNewsletter()
    {
        return $this->hasOne(StoreNewsletterSubscriber::className(), ['customer_id' => 'id']);
    }
}
