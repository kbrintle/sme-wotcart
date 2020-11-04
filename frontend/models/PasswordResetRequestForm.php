<?php

namespace frontend\models;

use common\components\CurrentStore;
use Yii;
use yii\base\Model;
use common\models\customer\Customer;
use common\components\Notify;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'emailExists'],
        ];
    }

    public function emailExists($attribute) //this is a fix for the unique email validator not working
    {
        if (!Customer::find()->where(["email" => $this->email])->andWhere(['is_active' => 1])->exists()) {
            self::error($attribute);
            return false;
        }
    }

    public function error($attribute)
    {
        $this->addError($attribute, 'Sorry, we are unable to reset your password.');
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Customer */
        $user = Customer::find()
            ->where([
                'email' => $this->email,
                'is_active' => 1
            ])
            ->one();

        if ($user) {
            if (!Customer::isPasswordResetTokenValid($user->access_token)) {
                $user->generatePasswordResetToken();
            }


            //'062c2fee-b982-405e-a132-13b611cfc6b1'
            $data = [
                "personalizations" => [[
                    'to' => [
                        [
                            "email" => $user->email,
                            "name" => $user->first_name . " " . $user->last_name
                        ],

                    ],

                    'dynamic_template_data' => [
                        "store_url" => CurrentStore::getStore()->url,
                        "token" => $user->access_token
                    ],
                ]],
                "from" => [
                    "email" => Yii::$app->params['from_email']['address'],
                    "name" => Yii::$app->params['from_email']['name']
                ],
                "template_id" => "d-4047833f1d2041d794f4f7bc32133ce5"
            ];

            if ($user->save()) {
                return Notify::sendJsonMail($data);
            }
        }
        return false;
    }
}
