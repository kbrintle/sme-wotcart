<?php

namespace backend\controllers;

use backend\components\CurrentUser;
use common\components\CurrentStore;
use common\components\helpers\PermissionHelper;
use common\models\core\Subregions;
use common\models\customer\CustomerMagento;
use PayPal\Api\Address;
use Yii;
use common\models\customer\Customer;
use common\models\customer\CustomerAddress;
use common\models\customer\search\CustomerSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\core\Store;
use common\models\customer\CustomerReward;
use common\models\core\Admin;
use common\models\customer\CustomerMagentoEntity;


/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    public function actionCreate()
    {
        $model = new Customer();
        $model->created_at = time();
        $model->local_store = CurrentStore::getStoreId();
        /**
         * Save the model
         */
        if ($post = Yii::$app->request->post()) {
            if ($model->load($post) && $model->validate()) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
                $model->save();
                CurrentStore::setStore($model->store_id); //change store to find the customer
                return $this->redirect(["customer/update/$model->id"]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'isCreate' => true
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $email = $model->email;
        if ($model)
            PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to update this customer.");

        if ($post = Yii::$app->request->post()) {
            $password = $post['Customer']['password'] == $model->password ? true : false;
            if ($model->load($post)) {
                if (!$password) {
                    $model->setPassword($model->password);
                    $model->generateAuthKey();
                }
                if ($model->email == $email) {
                    $model->save(false);
                } else {
                    $model->save(true);
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'isUpdate' => true
        ]);
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to remove this customer.");

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionAssignCustomersFromMagento()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $mageCustomers = CustomerMagento::find()->where(['skip' => false])->all();

        foreach ($mageCustomers as $c) {

            $e_customer = Customer::find()->where(['email' => $c->email])->one();
            //print_r($e_customer);
            if ($e_customer) {
                continue;
            }

            if ($c->website == 'admin') {
                $c->website = 'sme';
            }

            if ($c->group == 'Invoice Only' || $c->group == 'General' || $c->group == 'Wholesale Tax Exempt') {
//                echo $c->store;
                $store = Store::getStoreBySlug($c->website);
//                echo "Group is invoice only and the store is " . isset($store->url) ? $store->url : 'N/A';
            } else {
                $store = Store::getStoreByLegacyCustomerGroup($c->group);
            }


            if (!$store || !$c->email) {
                continue;
            }

            //Get ID
            $mageCustomersEntity = CustomerMagentoEntity::find()->where(['email' => $c->email])->one();
            if (!isset($mageCustomersEntity)) {
                continue;
            }
            try {
                $customer = new Customer();
                $customer->legacy_group = $c->group;
                $customer->id = $mageCustomersEntity->entity_id;
                $customer->email = $c->email;
                $customer->password = Yii::$app->security->generatePasswordHash('password');
                $customer->store_id = $store->id;
                $customer->first_name = $c->firstname;
                $customer->last_name = $c->lastname;
                $customer->auth_key = Yii::$app->security->generateRandomString();
                $customer->sales_rep = trim($c->sales_rep);
                $customer->created_at = time();
            } catch (\Exception $e) {
                print_r($e);
            }
            if ($customer->save(false)) {
//                try {
                $subregion = Subregions::find()->where(['name' => $c->billing_region])->one();

                $address = new CustomerAddress();
                $address->customer_id = $customer->id;
                $address->firstname = $c->billing_firstname;
                $address->lastname = $c->billing_lastname;
                $address->address_1 = $c->billing_street1;
                $address->address_2 = $c->billing_street2;
                $address->city = $c->billing_city;
                $address->region = $c->billing_region;
                $address->region_id = isset($subregion) ? $subregion->region_id : 0;
                $address->subregion_id = isset($subregion) ? $subregion->id : 0;
                $address->postcode = $c->billing_postcode;
                $address->phone = $c->billing_telephone;
                $address->fax = $c->billing_fax;
                $address->default_billing = true;
                $address->type = "billing";

                $address->save(false);

                $subregion = Subregions::find()->where(['name' => $c->shipping_region])->one();

                $address = new CustomerAddress();
                $address->customer_id = $customer->id;
                $address->firstname = $c->shipping_firstname;
                $address->lastname = $c->shipping_lastname;
                $address->address_1 = $c->shipping_street1;
                $address->address_2 = $c->shipping_street2;
                $address->city = $c->shipping_city;
                $address->region = $c->shipping_region;
                $address->region_id = isset($subregion) ? $subregion->region_id : 0;
                $address->subregion_id = isset($subregion) ? $subregion->id : 0;
                $address->postcode = $c->billing_postcode;
                $address->phone = $c->billing_telephone;
                $address->fax = $c->billing_fax;
                $address->default_shipping = true;
                $address->type = "shipping";

                $address->save(false);
//                } catch (\Exception $e) {
//                    print_r($e);
//                }
            } else {
                print_r($customer->errors);
            }
        }
    }

    public function actionAjaxAddress()
    {
        if ($post = Yii::$app->request->post()) {

            if (isset($post['action']) && !empty($post['action'])) {
                if (isset($post['id']) && !empty($post['id'])) {
                    switch ($post["action"]) {
                        case "create":
                            $model = new CustomerAddress();
                            $model->customer_id = $post['id'];
                            $model->save(false);
                            return json_encode(["form" => $this->renderPartial("partials/_addressform", ['address' => $model]), "info" => $this->renderPartial("partials/_addressinfo", ['address' => $model, 'new' => true])]);
                            break;
                        case "loadform":

                            if ($model = CustomerAddress::find()->where(['address_id' => $post['id']])->one()) {
                                return $this->renderPartial("partials/_addressform", ['address' => $model]);
                            }

                            break;
                        case "delete":
                            CustomerAddress::deleteAll(['address_id' => $post['id']]);
                            return true;
                            break;
                        case "defaultShipping":
                            if ($model = CustomerAddress::find()->where(['address_id' => $post['id']])->one()) {
                                if (isset($post['userId']) && !empty($post['userId'])) {
                                    CustomerAddress::updateAll(['default_shipping' => false], "customer_id = " . $post['userId']);
                                    $model->default_shipping = true;
                                    $model->save(false);
                                }
                            }
                            return true;
                            break;
                        case "defaultBilling":
                            if ($model = CustomerAddress::find()->where(['address_id' => $post['id']])->one()) {
                                if (isset($post['userId']) && !empty($post['userId'])) {
                                    CustomerAddress::updateAll(['default_billing' => false], "customer_id = " . $post['userId']);
                                    $model->default_billing = true;
                                    $model->save(false);
                                }
                            }
                            return true;
                            break;
                    }
                }
            } else if (isset($post['CustomerAddress']) && !empty($post['CustomerAddress'])) {

                if (empty($post['CustomerAddress']['address_id'])) {
                    $model = new CustomerAddress();
                } else {
                    $model = CustomerAddress::find()->where(['address_id' => $post['CustomerAddress']['address_id']])->one();
                    if (empty($model)) {
                        $model = new CustomerAddress();
                    }
                }
                if ($model->load($post)) {
                    $model->save(false);
                    return $this->renderPartial("partials/_addressinfo", ['address' => $model, 'new' => false]);
                    //   }
                }
            }
        }
    }

    public function actionAjaxRewardPoints()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['CustomerReward'])) {
                if (isset($post['CustomerReward']['points']) && isset($post['CustomerReward']['customer_id'])) {
                    $points = $post['CustomerReward']['points'];
                    if ($points == 0) {
                        return "No points to add or subtract";
                    }
                    if ($points < 0) {
                        $type = "sub";
                    } else {
                        $type = "add";
                    }
                    $customerId = $post['CustomerReward']['customer_id'];
                    if (strlen($points) > 0 && strlen($customerId) > 0) {
                        $usablePoints = CustomerReward::getUsablePoints($customerId);
                        if ($type == "sub" && abs($points) > $usablePoints) {
                            return "Cannot go lower then zero points";
                        }
                        if (CurrentUser::isAdmin()) {
                            $newPoints = new CustomerReward();
                            $newPoints->customer_id = $customerId;
                            $newPoints->points = $points;
                            $newPoints->type = $type;
                            $newPoints->points = $points;
                            $newPoints->order_id = "Authorized by " . Admin::findIdentity(CurrentUser::getUserId())->getInitials();
                            $newPoints->comments = "Authorized by " . Admin::findIdentity(CurrentUser::getUserId())->getInitials();
                            $newPoints->created_at = time();


                           // print_r($newPoints); die;
                            if (!$newPoints->validate()) {
                                return json_encode($newPoints->errors);
                            }
                            if ($newPoints->save(false)) {
                                return CustomerReward::getUsablePoints($customerId);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}