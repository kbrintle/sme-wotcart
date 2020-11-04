<?php

namespace backend\controllers;

use common\components\helpers\FormHelper;
use common\components\helpers\PermissionHelper;
use common\models\catalog\CatalogProduct;
use Yii;
use common\components\CurrentStore;
use common\models\core\Admin;
use common\models\core\Store;
use common\models\core\search\StoreSearch;
use yii\base\ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\settings\SettingsStore;
use common\models\settings\SettingsSeo;
use common\models\settings\SettingsPayment;
use common\models\settings\SettingsShipping;
use common\models\core\CoreConfig;
use common\models\catalog\CatalogStoreProduct;

/**
 * StoreController implements the CRUD actions for Store model.
 */
class StoreController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
            ],
        ];
    }

    public function actionRoute()
    {
        if (Yii::$app->user->isGuest) {
            $request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

            if (!strpos($request, 'site/login')) {
                Yii::$app->user->loginRequired()->send();
                exit(0);
            }
        } else {
            if (empty(Yii::$app->session['current_store'])) {
                $adminUser = Admin::findOne(Yii::$app->user->id);

                if (isset($adminUser)) {
                    if ($adminUser->isAdminUser()) {
                        CurrentStore::setStore(0);
                    } else {
                        CurrentStore::setStore(Admin::getDefaultStore());
                    }
                }
            }
        }

        return true;
    }

    /**
     * Lists all Store models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $store_id = CurrentStore::getStoreId();
        if ($store_id != false)
            $dataProvider->query->where(['s.id' => $store_id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Store model.
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
     * Creates a new Store model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Store();
        $model->updated_at = time();
        $model->created_at = time();
        $model->is_active  = true;
        if ($model->load(Yii::$app->request->post())) {
            $model->url = strtolower($model->url) ;
            if ($model->save()) {

                $model->refresh();

                //copy all settings from the all store
                $allSettingsStore = SettingsStore::find()->where(['store_id' => 0])->one();
                $newSettingsStore = new SettingsStore;
                $newSettingsStore->attributes = $allSettingsStore->attributes;
                $newSettingsStore->store_id = $model->id;
                $newSettingsStore->save();

                $allSettingsSeo = SettingsStore::find()->where(['store_id' => 0])->all();
                foreach ($allSettingsSeo as $row) {
                    $newSettingsSeo = new SettingsSeo;
                    $newSettingsSeo->load($row);
                    $newSettingsSeo->store_id = $model->id;
                    $newSettingsSeo->save();
                }

                $allSettingsPayment = SettingsPayment::find()->where(['store_id' => 0])->all();
                foreach ($allSettingsPayment as $row) {
                    $newSettingsPayment = new SettingsPayment;
                    $newSettingsPayment->load($row);
                    $newSettingsPayment->store_id = $model->id;
                    $newSettingsPayment->save();
                }

                $allSettingsShipping = SettingsShipping::find()->where(['store_id' => 0])->all();
                foreach ($allSettingsShipping as $row) {
                    $newSettingsShipping = new SettingsShipping;
                    $newSettingsShipping->load($row);
                    $newSettingsShipping->store_id = $model->id;
                    $newSettingsShipping->save();
                }
                $allCoreConfig = CoreConfig::find()->where(['store_id' => 0])->all();
                foreach ($allCoreConfig as $row) {
                    CoreConfig::saveConfig($row->path, $row->value, $model->id);
                }

                //Add all active products to newly created store
                CatalogStoreProduct::addProductsToStore($model->id);

                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Store model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byColumn($model->id, CurrentStore::getStoreId(), "Sorry, you don't have permission to update this store.");

        if ($model->load(Yii::$app->request->post())) {
            $model->url = filter_var($model->url, FILTER_SANITIZE_STRING);

            if ($model->save(false))
                return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Store model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byColumn($model->id, CurrentStore::getStoreId(), "Sorry, you don't have permission to update this store.");

        $model->is_deleted = true;

        if ($model->save(false))
            return $this->redirect(['index']);

        throw new ErrorException('Unable to delete store.');
    }


    /**
     * Deletes an existing Store model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAssign()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            //Set store using the CurrentStore component
            CurrentStore::setStore($data['store_id']);

            //Redirect to the referer and set store switcher
            return $this->redirect($_SERVER["HTTP_REFERER"]);
        } else {
            return false;
        }
    }


    /**
     * Gets an existing Store settings.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSettings()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            //Set store using the CurrentStore component
            CurrentStore::setStore($data['store_id']);

            //Redirect to the referer and set store switcher
            return $this->redirect($_SERVER["HTTP_REFERER"]);

        }
    }

    /**
     * Finds the Store model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Store the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Store::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}