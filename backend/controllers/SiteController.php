<?php

namespace backend\controllers;

use common\models\core\Admin;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\core\AdminLoginForm;
use yii\base\InvalidParamException;
use common\components\CurrentStore;
use common\models\core\AdminPasswordResetRequestForm;
use common\models\core\AdminResetPasswordForm;
use yii\web\BadRequestHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['request-password-reset', 'login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect('dashboard');
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'clean';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminLoginForm();

        if ($model->load(Yii::$app->request->post())) {

            //var_dump($model);die;

             /*if ($customer = Admin::findByEmail($model->email)) {
                 if ($customer->updated_at < 1547571600) { //1547668800 = Jan 16 2019 3pm EST
                     $store = CurrentStore::getStore();
                     return $this->render('requestPasswordResetToken', [
                         'model' => new AdminPasswordResetRequestForm(),
                         "prompt" => "Welcome to the new Store Admin!<br>In order to keep our security up to date,<br> Please reset your password<br><br>"
                     ]);
                 }
             }*/

            if ($model->login()) {
                $this->redirect('/admin/dashboard');
            }
        }
        return $this->render('login', [
            'model' => $model
        ]);

    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {

        $auth_key = Yii::$app->user->identity->getAuthKey();

        Yii::$app->user->logout(true);

        return $this->goHome();
        /**
         * Do SSO login
         */
        /*    $req        = Yii::$app->request;
            $ref        = $req->get('ref');
            if($ref){
                return $this->redirect(urldecode($ref), 302);
            }*/

        //return $this->redirect(Yii::$app->params['sso']['logout'] . '?auth=' . urlencode($auth_key) . '&ref=' . urlencode(Yii::$app->params['sso']['logout_redirect']) , 302);

    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordResetSuccess()
    {
        var_dump("444");die;
        $this->layout = 'clean';
        return $this->render('requestPasswordResetTokenSuccess', [
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        var_dump("111");die;
        $this->layout = 'clean';
        $model = new AdminPasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->redirect([StoreUrl::to('account/request-password-reset-success')]);
            } else {
                $model->error("email");
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword()
    {
        $token = Yii::$app->request->get('token');

        try {
            $model = new AdminResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
