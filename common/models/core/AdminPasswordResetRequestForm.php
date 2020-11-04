<?php

namespace common\models\core;

use common\components\CurrentStore;
use Yii;
use yii\base\Model;
use common\models\core\Admin;
use common\components\Notify;

/**
 * Password reset request form
 */
class AdminPasswordResetRequestForm extends Model
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
        if (!Admin::find()->where(["email" => $this->email])->andWhere(['is_active' => 1])->exists()) {
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
        /* @var $admin Customer */
        $admin = Admin::find()
            ->where([
                'email' => $this->email,
                'is_active' => 1
            ])
            ->one();

        if ($admin) {
            if (!Admin::isPasswordResetTokenValid($admin->access_token)) {
                $admin->generatePasswordResetToken();
            }


            //'062c2fee-b982-405e-a132-13b611cfc6b1'
            $data = [
                "personalizations" => [[
                    'to' => [
                        [
                            "email" => $admin->email,
                            "name" => "$admin->first_name $admin->last_name"
                        ],

                    ],

                    'dynamic_template_data' => [
                        "store_url" => "admin",
                        "token" => $admin->access_token
                    ],
                ]],
                "from" => [
                    "email" => Yii::$app->params['from_email']['address'],
                    "name" => Yii::$app->params['from_email']['name']
                ],
                "template_id" => "d-4047833f1d2041d794f4f7bc32133ce5"
            ];

            if ($admin->save()) {
                return Notify::sendJsonMail($data);
            }
        }
        return false;
    }
}
