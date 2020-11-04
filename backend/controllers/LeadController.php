<?php

namespace backend\controllers;

use common\models\customer\Customer;
use common\models\customer\CustomerAddress;
use Yii;
use common\models\customer\Lead;
use common\models\customer\search\LeadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\Notify;

/**
 * LeadController implements the CRUD actions for Lead model.
 */
class LeadController extends Controller
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
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lead model.
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
     * Creates a new Lead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lead();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConvert($id)
    {
        $lead = $this->findModel($id);
        $customer = new Customer();
        $customer->store_id = $lead->store_id;
        //$customer->group_id
        $customer->email = $lead->contact_email;
        $name = ltrim(rtrim($lead->ordering_contact_name));
        $name = explode(" ", $name);
        if (isset($name[0])) {
            $customer->first_name = $name[0];
        }
        if (isset($name[1])) {
            $customer->last_name = $name[1];
        }
        if (!isset($customer->first_name)) {
            $customer->first_name = "";
        }
        if (!isset($customer->last_name)) {
            $customer->last_name = "LAST NAME";
        }
        $customer->created_at = time();
        $customer->is_active = 1;
        $customer->password = Yii::$app->getSecurity()->generatePasswordHash(self::generateRandomString());
        $customer->generateAuthKey();
        if ($customer->save()) {
            $customerAddress = new CustomerAddress();
            $customerAddress->firstname = $customer->first_name;
            $customerAddress->lastname = $customer->last_name;
            $customerAddress->address_1 = $lead->clinic_address;
            $customerAddress->region_id = $lead->clinic_state;
            $customerAddress->city = $lead->clinic_city;
            $customerAddress->postcode = $lead->clinic_zip;
            return $this->redirect(['//customer/view', 'id' => $customer->id]);
        } else {
            $errors = "";
            foreach ($customer->errors as $error) {
                $errors .= $error[0] . " ";

            }
            Yii::$app->session->setFlash('error', $errors);
            return $this->redirect(['//lead/update', 'id' => $lead->id]);
        }
    }

    /**
     * Deletes an existing Lead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function sendUserPasswordEmail($lead)
    {
        Notify::sendMail('Approved ' . Yii::$app->name, [$lead->contact_email], 'customer/new_lead_email', $data = [
            'lead' => $lead
        ]);
    }

    /**
     * Finds the Lead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Lead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}