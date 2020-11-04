<?php
namespace common\models\core;

use common\models\core\Admin;
use yii\base\Model;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class AdminResetPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\Customer
     */
    private $_admin;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        //$this->_admin = Customer::findByPasswordResetToken($token);
        $this->_admin = Admin::find()->where(['access_token'=>$token])->one();

        //print_r($this->_admin);  die;
        if (!$this->_admin) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $admin = $this->_admin;
        $admin->setPassword($this->password);
        $admin->removePasswordResetToken();

        return $admin->save(false);
    }
}
