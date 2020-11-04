<?php

namespace backend\models;

use common\models\core\Admin;

/**
 * Class AttributeForm
 * @package backend\models
 */
class AdminUserForm extends Admin
{

    public $role_id;
    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_confirm;
    public $created_at;
    public $updated_at;
    public $is_active;
    public $is_deleted;
    public $isNewRecord = true;

    public function rules()
    {
        return [

            [['email'], 'unique', 'on' => 'create'],
//            [['email'], 'unique', 'on' =>'update', 'when' => function ($model) {
//                if ($model->email !== $model->username) {
//                    return true;
//                }
//            }, 'message' => "Email can not be changed"],

            [['first_name', 'last_name', 'email'], 'required'],
            [['first_name', 'last_name'], 'string'],
            [['email'], 'email'],
            [['role_id'], 'integer'],
            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'required', 'on' => 'create'],
            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['role_id', 'username', 'first_name', 'last_name', 'email', 'password', 'password_confirm'];
        $scenarios['create'] = ['role_id', 'username', 'first_name', 'last_name', 'email', 'password', 'password_confirm'];
        return $scenarios;
    }

    public function fillFormUpdate($admin)
    {
        $adminForm = new AdminUserForm(['scenario' => 'update']);
        $adminForm->role_id = $admin->role_id;
        $adminForm->username = $admin->username;
        $adminForm->first_name = $admin->first_name;
        $adminForm->last_name = $admin->last_name;
        $adminForm->email = $admin->email;
        $adminForm->created_at = $admin->created_at;
        $adminForm->updated_at = $admin->updated_at;
        $adminForm->is_active = $admin->is_active;
        $adminForm->is_deleted = $admin->is_deleted;
        $adminForm->isNewRecord = $admin->isNewRecord;
        return $adminForm;
    }

    public function saveAdmin($new = false, $id = null)
    {
        if ($new == true) {
            $admin = new Admin();
        } else {
            $admin = Admin::findIdentity($id);
        }

        $admin->role_id = $this->role_id;
        $admin->username = $this->email;
        $admin->email = $this->email;
        $admin->first_name = $this->first_name;
        $admin->last_name = $this->last_name;
        $admin->setPassword($this->password);
        $admin->auth_key = '';
        $admin->updated_at = $this->created_at;
        $admin->is_active = 1;
        $admin->is_deleted = 0;

        if (!$admin->save()) {
            var_dump($admin->getErrors());
            die();
        } else {
            return true;
        }
    }
}
