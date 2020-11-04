<?php
namespace common\models\core;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\components\CurrentUser;
use yii\db\Expression;


// old `admin` properties
//integer $id
//string $username
//string $email
//string $password
//string $auth_key
//string $accessToken
//string $first_name
//string $last_name
//string $time_zone
//integer $role_id
//integer $store_id
//integer $created_at
//integer $updated_at
//integer $active
//integer $is_deleted
//integer $last_login

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property integer $role_id
 * @property integer $avatar
 * @property integer $username
 * @property integer $password
 * @property integer $email
 * @property integer $auth_key
 * @property integer $access_token
 * @property integer $first_name
 * @property integer $last_name
 * @property integer $time_zone
 * @property integer $is_active
 * @property integer $updated_at
 * @property integer $is_deleted
 * @property integer $created_at
 * @property integer $last_login
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role_id', 'created_at', 'updated_at', 'is_active', 'is_deleted', 'last_login'], 'integer'],
            [['username', 'email', 'password', 'password_confirm', 'auth_key', 'access_token', 'first_name', 'last_name', 'time_zone'], 'safe'],
        ];
    }

    const ROLE_ADMIN = 1;
    const ROLE_STORE = 2;
    const ROLE_OPS   = 3;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * Change the TimestampBehavior here to match the new fields from the LMS
     *
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s')),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role',
            'avatar' => 'Avatar',
            'username' => 'User Name',
            'password' => 'Password',
            'First Name' => 'First Name',
            'Last Name' => 'Last Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'last_login' => 'Last Login'
        ];
    }


    public function getRole()
    {
        return $this->hasOne(AdminRole::className(), ['id' => 'role_id']);
    }

    public function getInitials()
    {
        return substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1);
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
        return static::findOne(['username' => $username, 'is_active' => true]);
    }

    /**
     * Finds user by email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'is_active' => true]);
    }


    public static function findByAuthKey($auth_key){
        if($auth_key){
            return static::findOne(['auth_key' => $auth_key]);
        }
        return null;
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

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
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
     * @inheritdoc
     */
    public function isAdminUser()
    {
        if($this->role_id == self::ROLE_ADMIN){
            return true;
        }
        else{
            return false;
        }

    }

    /**
     * @inheritdoc
     */
    public function isStoreUser()
    {
        if($this->role_id == self::ROLE_STORE){
            return true;
        }
        else{
            return false;
        }

    }


    public function getAdminRole()
    {
        return $this->hasOne(AdminRole::className(), ['id' => 'role_id']);
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
        $this->accessToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->accessToken = null;
    }

    public static function getDefaultStore(){
        $adminStores = AdminStore::find()->where(['admin_id' => CurrentUser::getUserId()])->indexBy('store_id')->one();
        return $adminStores ? $adminStores->store_id : null;
    }

    public static function avatarColor($uid = 0) {
        $uid      = ($uid) ? $uid : Yii::$app->user->id;
        $initials = str_split(self::findIdentity($uid)->getInitials());
        switch ($initials[0]) {
            case 'A':
            case 'B':
            case 'C':
            case 'D':
                return 'avatarGrey';
                break;
            case 'E':
            case 'F':
            case 'G':
            case 'H':
                return 'avatarBlue';
                break;
            case 'I':
            case 'J':
            case 'K':
            case 'L':
                return 'avatarRed';
                break;
            case 'M':
            case 'N':
            case 'O':
            case 'P':
                return 'avatarYellow';
                break;
            case 'Q':
            case 'R':
            case 'Z':
            case 'T':
                return 'avatarLazur';
                break;
            case 'U':
            case 'V':
            case 'W':
            case 'X':
                return 'avatarGreen';
                break;
            case 'Y':
            case 'S':
                return 'avatarPurple';
                break;
        }
    }
    /**
     * Returns the desired $property of a user
     * If no $uid is provided the current user is assumed
     *
     * @param  string  $property
     * @param  integer $uid
     * @return string|null
     */
//    public static function getRole($property, $uid = 0)
//    {
//        $uid = ($uid) ? $uid : Yii::$app->user->id;
//
//        if (self::isSuperAdmin($uid)) return self::getRoleProperty(self::SUPERADMIN, $property);
//        elseif (self::isAdmin($uid)) return self::getRoleProperty(self::ADMIN, $property);
//        elseif (self::isRegionalOperationsManager($uid)) return self::getRoleProperty(self::REGIONAL_OPERATIONS_MANAGER, $property);
//        elseif (self::isOwner($uid)) return self::getRoleProperty(self::OWNER, $property);
//        elseif (self::isRegionalManager($uid)) return self::getRoleProperty(self::REGIONAL_MANAGER, $property);
//        elseif (self::isStoreManager($uid)) return self::getRoleProperty(self::STORE_MANAGER, $property);
//        elseif (self::isAssociate($uid)) return self::getRoleProperty(self::ASSOCIATE, $property);
//        else return null;
//    }

    /**
     * Determines whether the user has the 'superadmin' role
     * If no $uid is provided the current user is assumed
     *
     * @param  integer $uid
     * @return boolean
     */
    public static function isSuperAdmin($uid = 0)
    {
        $uid = ($uid) ? $uid : Yii::$app->user->id;

        return in_array($uid,
            Yii::$app->authManager->getUserIdsByRole(
                self::getRoleProperty(self::SUPERADMIN, 'slug')
            )
        );
    }

    /**
     * Determines whether the user has the 'admin' role
     * During isAdmin checks, superadmins evaluate true
     * If no $uid is provided the current user is assumed
     *
     * @param  integer $uid
     * @return boolean
     */
    public static function isAdmin($uid = 0)
    {
        $uid = ($uid) ? $uid : Yii::$app->user->id;

        return in_array($uid,
            Yii::$app->authManager->getUserIdsByRole(
                self::getRoleProperty(self::ADMIN, 'slug')
            )
        );
    }
}
