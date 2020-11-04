<?php

namespace frontend\controllers;

use app\components\StoreUrl;
use backend\components\CurrentUser;
use common\components\CurrentStore;
use common\components\Notify;
use common\models\core\Subregions;
use common\models\customer\CustomerActivity;
use common\models\sales\SalesOrder;
use common\models\settings\SettingsStore;
use common\models\store\StoreNewsletterSubscriber;
use frontend\components\CurrentCustomer;
use frontend\models\AccountInformationForm;
use frontend\models\AccountResetPasswordForm;
use frontend\models\ClinicSpotlightForm;
use frontend\models\GuestForm;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\RegisterForm;
use common\models\customer\Customer;
use common\models\customer\CustomerAddress;
use yii\filters\AccessControl;
use common\models\core\CountryRegion;

class AccountController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'information', 'update-password', 'orders', 'addresses', 'overview', 'order', 'subscribe', 'unsubscribe', 'login', 'logout', 'register'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'register', 'request-password-reset'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'information', 'update-password', 'orders', 'addresses', 'overview', 'order', 'subscribe', 'unsubscribe', 'logout', 'register'],
                        'roles' => ['@'],
                    ],
                ], 'denyCallback' => function ($rule, $action) {
                    return $this->redirect([StoreUrl::to('account/login')]);
                }
            ],
        ];
    }

    public function init()
    {
        $this->layout = 'main';

    }

    public function actionIndex()
    {
        return $this->redirect([StoreUrl::to('account/overview')]);
    }

    public function actionInformation()
    {
        $this->view->title = "My Account";

        $model = new AccountInformationForm();
        $states = ArrayHelper::map(Subregions::find()->states()->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post())) {
            $result = $model->save();
            if ($result === true) {
                Yii::$app->session->setFlash('success', 'Your account was successfully updated');
            } else if ($result === false) {
                Yii::$app->session->setFlash('error', 'Something went wrong while updating your account');
            } else {
                Yii::$app->session->setFlash('error', $result);
            }
        }

        return $this->render('information', [
            'model' => $model,
            'states' => $states
        ]);
    }

    public function actionUpdatePassword()
    {
        $model = new AccountResetPasswordForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $model = new AccountResetPasswordForm();
                Yii::$app->session->setFlash('success', 'Your password was successfully updated');
            } else {
                Yii::$app->session->setFlash('error', 'Something went wrong while updating your password');
            }
        }

        return $this->render('update-password', [
            'model' => $model
        ]);
    }

    public function actionOrders()
    {
        $this->view->title = "My Orders";
        return $this->render('orders', ['customer' => CurrentCustomer::getCustomer()]);
    }

    public function actionClinicSpotlight()
    {
        $this->view->title = "Clinic Spotlight Application";

        $model = new ClinicSpotlightForm();
        if ($post = Yii::$app->request->post()) {
            $post = $post['ClinicSpotlightForm'];

            $data = [
                'name'          => $post['clinic_name'],
                'address'       => $post['clinic_address'],
                'locations'     => $post['locations'],
                'response'      => $post['response'],
                'social'         => implode(', ', $post['social']),
                'social_other'   => $post['social_other'],
                'sending'        => implode(', ', $post['sending']),
                'sending_other'  =>  $post['sending_other'],
                'contact_name'   =>  $post['contact_name'],
            ];

            if($model->sendEmail($data)){
                Yii::$app->session->setFlash('success', 'Thank you for submitting your application. We will contact you shortly.');
                return $this->redirect([StoreUrl::to('account')]);
            }else{
                Yii::$app->session->setFlash('error', 'There was an issue submitting your application');
            }
        }


        return $this->render('clinic_spotlight', ['model'=>$model]);
    }

    public function actionAddresses()
    {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
                if (isset($post['action']) && isset($post['aid'])) {
                    $states = ArrayHelper::map(CountryRegion::find()->where(['country_id' => 'US'])->all(), 'id', 'code');
                    switch ($post['action']) {
                        case "edit":
                            $model = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'address_id' => $post['aid']]);
                            return $this->renderPartial('_partials/_address-edit-modal', ['model' => $model, 'states' => $states, 'isNewRecord' => false]);
                            break;
                        case "new":
                            $model = new CustomerAddress();
                            return $this->renderPartial('_partials/_address-edit-modal', ['model' => $model, 'states' => $states, 'isNewRecord' => true]);
                            break;
                        case "delete":
                            $model = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'address_id' => $post['aid']]);
                            return $this->renderPartial('_partials/_address-delete-modal', ['model' => $model]);
                            break;
                    }
                }
            }
            return true;
        }

        if ($post = Yii::$app->request->post()) {
            switch ($post["CustomerAddress"]['action']) {
                case "save":
                    if (isset($post["CustomerAddress"]['address_id']) && !empty($post["CustomerAddress"]['address_id'])) {
                        $address_id = $post["CustomerAddress"]['address_id'];
                        $model = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'address_id' => $address_id]);
                    } else {
                        $model = new CustomerAddress();
                        $model->customer_id = CurrentUser::getUserId();
                    }

                    $model->load($post);

                    if ($model->default_shipping === "1") {
                        if ($default_shipping = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'default_shipping' => "1"])) {
                            $default_shipping->default_shipping = "0";
                            $default_shipping->save();
                        }
                    }

                    if ($model->default_billing === "1") {
                        if ($default_billing = CustomerAddress::findOne(['customer_id' => CurrentUser::getUserId(), 'default_billing' => "1"])) {
                            $default_billing->default_billing = "0";
                            $default_billing->save();
                        }
                    }

                    if ($region = CountryRegion::getRegionById($model->region_id)) {
                        $model->region = $region->default_name;
                    }

                    if (!$model->save()) {
                        throw new NotFoundHttpException('Error saving Address');
                    }
                    break;
                case "delete":
                    if (isset($post["CustomerAddress"]['address_id'])) {
                        $address_id = $post["CustomerAddress"]['address_id'];
                        CustomerAddress::deleteAll(['customer_id' => CurrentUser::getUserId(), 'address_id' => $address_id]);
                    }
                    break;
            }
        }

        $this->view->title = "My Addresses";
        return $this->render('addresses', ['customer' => CurrentCustomer::getCustomer(), 'addresses' => CustomerAddress::findAll(['customer_id' => CurrentUser::getUserId()])]);
    }

    public function actionOverview()
    {
        if (!Yii::$app->user->isGuest) {
            $this->view->title = "My Account";
            return $this->render('overview', [
                'customer' => CurrentCustomer::getCustomer(),
                'shipping' => CurrentCustomer::getCustomerShippingAddress(),
                'billing' => CurrentCustomer::getCustomerBillingAddress()]);
        } else {
            if (Yii::$app->user->logout(true)) {
                return $this->redirect([StoreUrl::to('account/login')]);
            }
        }
    }

    public function actionOrder($id)
    {
        $this->view->title = "My Orders";
        $order = SalesOrder::findOne(['order_id' => $id]);

        if (!$order) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('order_detail',
            [
                'order' => $order,
                'customer' => CurrentCustomer::getCustomer()
            ]);
    }


    public function actionSubscribe()
    {
        StoreNewsletterSubscriber::subscribe();
        $this->redirect('overview');
    }

    public function actionUnsubscribe()
    {
        StoreNewsletterSubscriber::unsubscribe();
        $this->redirect('overview');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'clean';

        if (!Yii::$app->user->isGuest) {
            if (isset($_GET['checkout'])) {
                return $this->redirect(StoreUrl::to('checkout'));
            }
            if (isset($_GET['redir'])) {
                return $this->redirect($_GET['redir']);
            }
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {

                if (isset($_GET['checkout'])) {
                    return $this->redirect(StoreUrl::to('checkout'));
                }
                if (isset($_GET['redirect'])) {
                    return $this->redirect($_GET['redirect']);
                }

                $storeId = 0;
                if ($user = Customer::find()->where(['id' => Yii::$app->user->id])->one()) {
                    $storeId = $user->store_id;
                }

                return $this->redirect(StoreUrl::to('account/overview', $storeId));
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionGuest()
    {
        $model = new GuestForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->setSession()) {
                return $this->redirect(StoreUrl::to('checkout'));
            }
        }
        return $this->redirect([StoreUrl::to("/")]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $this->view->title = "Logout";

        CustomerActivity::logoutActivity();
        $store_id = CurrentStore::getStoreId();
        Yii::$app->user->logout();

        return $this->redirect([StoreUrl::to("/", $store_id)]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRegister()
    {
        $settings = SettingsStore::getSettings();

        $this->layout = 'clean';
        $this->view->title = "Register";
        if (!Yii::$app->user->isGuest) {
            $this->redirect([StoreUrl::to('account/overview')]);
        }
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($user = $model->register()) {
                    if (YII_ENV_DEV) {
                        $to[] = ["email" => 'christian+dev@wideopentech.com'];
                    } else {
                        $to[] = ["email" => 'suzanne@smeincusa.com'];
                        $to[] = ["email" => 'kim@smeincusa.com'];
                    }
                    //Admin Email
                    $data = [
                        "personalizations" => [[
                            'to' => $to,
                            'dynamic_template_data' => [
                                "store_url" => CurrentStore::getStore()->url,
                            ],
                        ]],
                        "from" => [
                            "email" => Yii::$app->params['from_email']['address'],
                            "name" => Yii::$app->params['from_email']['name']
                        ],
                        "template_id" => "d-57ffa397ec104b9287d1b920b5a7cd21"
                    ];
                    Notify::sendJsonMail($data);

                    //User Email
                    $data = [
                        "personalizations" => [[
                            'to' => [
                                [
                                    "email" => ($settings && $settings->general_email) ? $settings->general_email : 'kim@smeincusa.com',
                                    "name" => CurrentStore::getStore()->name
                                ],
                                [
                                    "email" => 'kim@smeincusa.com',
                                    "name" => 'Kim Reyna'
                                ],

                            ],

                            'dynamic_template_data' => [
                                "store_url" => CurrentStore::getStore()->url,
                            ],
                        ]],
                        "from" => [
                            "email" => Yii::$app->params['from_email']['address'],
                            "name" => Yii::$app->params['from_email']['name']
                        ],
                        "template_id" => "d-57ffa397ec104b9287d1b920b5a7cd2"
                    ];


                    Notify::sendJsonMail($data);
                    //$this->sendNewUserEmailToAdmin($user); //TODO figure out this function
//                    die;
                    return $this->redirect(['/sme/account/register-thank-you']);
                }
            } else {
                var_dump($model->errors);
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public
    function actionRegisterThankYou()
    {
        return $this->render('register_thank_you', [
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public
    function actionRequestPasswordResetSuccess()
    {
        $this->layout = 'clean';
        return $this->render('requestPasswordResetTokenSuccess', [
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public
    function actionRequestPasswordReset()
    {
        $this->layout = 'clean';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                //CurrentStore::setStore($model->store_id);
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
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $model->resetPassword()) {
                Yii::$app->session->setFlash('success', 'New password was saved.');
            }
            CurrentStore::setStore($user->store_id);
            return $this->redirect([StoreUrl::to('account/login')]);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public
    function sendNewUserEmail($lead)
    {
        Notify::sendMail('Welcome To ' . Yii::$app->name, [$lead->contact_email], 'customer/new_lead_email', $data = [
            'lead' => $lead
        ]);
    }

    public
    function sendNewUserEmailToAdmin($lead)
    {
        /*        $store_id = CurrentStore::getStoreId();
                if (empty($store_id) || !isset($store_id)) {
                    $store_id = 0;
                }

                Notify::sendMail('Welcome To ' . Yii::$app->name, [$lead->contact_email], 'customer/new_lead_email', $data = [
                    'lead' => $lead
                ]);*/
    }


}