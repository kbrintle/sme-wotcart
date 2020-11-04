<?php

namespace backend\controllers;

use common\components\CurrentStore;
use common\models\core\Store;
use common\models\sales\ShippingTableRate;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use common\models\settings\SettingsPayment;
use common\models\settings\SettingsShipping;
use frontend\components\CurrentCustomer;
use Yii;
use common\models\store\Location;
use common\models\store\search\LocationSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use common\models\core\CoreConfig;

/**
 * SettingsController implements the CRUD actions for Settings models.
 */
class SettingsController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
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
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $settingsStore     = SettingsStore::find()->one();
        $settingsSeo       = SettingsSeo::find()->one();
        $settingsPayment   = SettingsPayment::find()->one();
        $settingsShipping  = SettingsShipping::find()->one();

        if(!isset($settingsStore)){
            $allSettings = SettingsStore::find()->where(['store_id'=>0])->one();
            $settingsStore = new SettingsStore();
            $settingsStore->attributes = $allSettings->attributes;

        }
        if(!isset($settingsSeo)){
            $allSettings = SettingsSeo::find()->where(['store_id'=>0])->one();
            $settingsSeo = new SettingsSeo();
            $settingsSeo->attributes = $allSettings->attributes;
        }
        if(!isset($settingsPayment)){
            $allSettings = SettingsPayment::find()->where(['store_id'=>0])->one();
            $settingsPayment = new SettingsPayment();
            $settingsPayment->attributes = $allSettings->attributes;
        }
        if(!isset($settingsShipping)){
            $allSettings = SettingsPayment::find()->where(['store_id'=>0])->one();
            $settingsShipping = new SettingsShipping();
            $settingsShipping->attributes = $allSettings->attributes;
        }

        if ($data = Yii::$app->request->post()) {
            //Load model data

            $settingsStore->load($data);

            $settingsStore->logo = UploadedFile::getInstance($settingsStore, 'logo');

            if (isset($data['SettingsStore']['supervisor_active'])) {
                CoreConfig::saveConfig('general/supervisor/active', $data['SettingsStore']['supervisor_active'], CurrentStore::getStoreId());
            }
           /* if (isset($data['SettingsStore']['banner_type'])) {
                CoreConfig::saveConfig('design/nav/banner_type', $data['SettingsStore']['banner_type'], CurrentStore::getStoreId());
            }
            if (isset($data['SettingsStore']['banner_text'])) {
                CoreConfig::saveConfig('design/nav/banner_text', $data['SettingsStore']['banner_text'], CurrentStore::getStoreId());
            }
            if (isset($data['SettingsStore']['banner_url'])) {
                $settingsStore->banner_url = $data['SettingsStore']['banner_url'];
                CoreConfig::saveConfig('design/nav/banner_url', $data['SettingsStore']['banner_url'], CurrentStore::getStoreId());
            }*/
            if (isset($data['SettingsStore']['supervisor_order_threshold'])) {
                CoreConfig::saveConfig('general/supervisor/threshold', $data['SettingsStore']['supervisor_order_threshold'], CurrentStore::getStoreId());
            }
            if (isset($data['SettingsStore']['supervisor_email'])) {
                CoreConfig::saveConfig('general/supervisor/email', $data['SettingsStore']['supervisor_email'], CurrentStore::getStoreId());
            }


            if (isset($data['ShippingRates']) && isset($data['SettingsShipping'])) {
                foreach ($data['ShippingRates'] as $rate) {
                    $tableRate = null;
                    if (array_key_exists('id', $rate)) { //existing option update
                        $tableRate = ShippingTableRate::findOne(['id' => $rate['id'], 'store_id' => CurrentStore::getStoreId()]);
                    }
                    if (!isset($tableRate)) {
                        $tableRate = new ShippingTableRate();
                    }
                    $tableRate->store_id = CurrentStore::getStoreId();
                    $tableRate->price = $rate['price'];
                    $tableRate->cost = $rate['cost'];

                    if ($tableRate->validate()) {
                        $tableRate->save();
                    }
                }
            } else {
                ShippingTableRate::deleteAll(['store_id' => CurrentStore::getStoreId()]);
            }

            if (isset($data['SettingsStore']['logo'])) {
                if (isset($settingsStore->logo) && !empty($settingsStore->logo)) {
                    $filename = '/logos/' . $settingsStore->logo;
                    $location = Yii::getAlias("@frontend") . '/web/uploads';
                    if (!empty($settingsStore->logo) && $settingsStore->logo->saveAs($location . $filename)) {
                        $settingsStore->logo = $filename;
                        CoreConfig::saveConfig('general/design/logo', $settingsStore->logo, CurrentStore::getStoreId());
                    } else {
                        return false;
                    }
                }
            }

            $settingsSeo->load($data);
            $settingsPayment->load($data);
            $settingsShipping->load($data);

//            if(isset($data['SettingsStore'])) {
//                $settingsStore->is_cart = $data['SettingsStore']['is_cart'];
//                $settingsStore->has_pricing = $data['SettingsStore']['has_pricing'];
//            }
//
//            $store = CurrentStore::getStore() ? CurrentStore::getStore() : 0 ;
//            if($store){
//                $store->is_cart = (isset($settingsStore->is_cart) && !empty($settingsStore->is_cart)) ? $settingsStore->is_cart : $settingsStore->is_cart;
//                $store->has_pricing = (isset($settingsStore->has_pricing) && !empty($settingsStore->has_pricing)) ? $settingsStore->has_pricing : $settingsStore->has_pricing;
//                $store->save(false);
//            }

            //Set all models to their respective current Store
            $settingsStore->store_id = CurrentStore::getStoreId();
            $settingsSeo->store_id = CurrentStore::getStoreId();
            $settingsShipping->store_id = CurrentStore::getStoreId();

            CoreConfig::saveConfig('payment/tax/enabled',$settingsPayment->calculate_tax, CurrentStore::getStoreId() );

            //Save data
            $settingsStore->save(false);
            $settingsSeo->save(false);
            if(isset($settingsPayment) && !empty($settingsPayment)){
                $settingsPayment->store_id = CurrentStore::getStoreId();
                $settingsPayment->stripe_title = "Credit Card";
                $settingsPayment->stripe_payment_countries = "United States";
                $settingsPayment->stripe_new_order_status = "Pending";
                //echo '<pre>';var_dump($data,$settingsPayment);die;
                $settingsPayment->save(false);
            }

            $settingsShipping->save(false);

            return Yii::$app->getResponse()->redirect(['settings/update']);


        }
        return $this->render('update', [
            'settingsStore'    => $settingsStore,
            'settingsSeo'      => $settingsSeo,
            'settingsPayment'  => $settingsPayment,
            'settingsShipping' => $settingsShipping,
        ]);
    }


    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPolicy()
    {
        $settingsStore     = SettingsStore::find()->one();


        if(!$settingsStore){
            $settingsStore = new SettingsStore();
        }


        if(Yii::$app->request->post()){

            //Load model data
            $settingsStore->load(Yii::$app->request->post());

            //Set all models to their respective current Store
            $settingsStore->store_id = CurrentStore::getStoreId();

            //Save data
            $settingsStore->save(false);

            return Yii::$app->getResponse()->redirect(['settings/policy']);


        } else {
            return $this->render('policy', [
                'settingsStore'    => $settingsStore,
            ]);
        }
    }
    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHomepage()
    {
        $settingsStore     = SettingsStore::find()->one();


        if(!$settingsStore){
            $settingsStore = new SettingsStore();
        }


        if(Yii::$app->request->post()){

            //Load model data
            $settingsStore->load(Yii::$app->request->post());


            //Set all models to their respective current Store
            $settingsStore->store_id = CurrentStore::getStoreId();

            //Save data
            $settingsStore->save(false);

            return Yii::$app->getResponse()->redirect(['settings/homepage']);


        } else {
            return $this->render('homepage', [
                'settingsStore'    => $settingsStore,
            ]);
        }
    }

    public function actionAbout()
    {
        $settingsStore  = SettingsStore::find()->one();


        if(!$settingsStore){
            $settingsStore = new SettingsStore();
        }


        if(Yii::$app->request->post()){

            //Load model data
            $settingsStore->load(Yii::$app->request->post());


            //Set all models to their respective current Store
            $settingsStore->store_id = CurrentStore::getStoreId();

            //Save data
            $settingsStore->save(false);

            return Yii::$app->getResponse()->redirect(['settings/about']);


        } else {
            return $this->render('about', [
                'settingsStore'    => $settingsStore,
            ]);
        }
    }

    /**
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}