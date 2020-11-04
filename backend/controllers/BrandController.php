<?php

namespace backend\controllers;

use app\models\CatalogStorebrands;
use backend\components\CurrentUser;
use common\components\helpers\PermissionHelper;
use common\models\catalog\CatalogProduct;
use common\models\catalog\search\CatalogBrandSearch;
use Yii;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogBrand;
use yii\web\Controller;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CurrentStore;
use common\models\core\Store;
use yii\web\UploadedFile;

/**
 * BrandController implements the CRUD actions for CatalogBrand model.
 */
class BrandController extends Controller
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
     * Lists all CatalogBrand models.
     * @return mixed
     */
    public function actionIndex()
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        // For super admin index
        $searchModel = new CatalogBrandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // For store admin index
        if (CurrentStore::isNone()) {
            $brands = CatalogBrand::find()
                ->where([
                    'store_id' => 0,
                    'is_active' => true,
                    'is_deleted' => false
                ])
                ->orderBy(['name'=>SORT_ASC])
                ->all();
        } else {
            $brands = CatalogBrand::find()
                ->where([
                    'store_id'   => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'is_active'  => true,
                    'is_deleted' => false
                ])
                ->orderBy(['name'=>SORT_ASC])
                ->all();
        }

        return $this->render('index', [
            'brands' => $brands,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogBrand model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CatalogBrand model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        $model = new CatalogBrand();

        if ($model->load(Yii::$app->request->post())) {
            $model->slug       = preg_replace("/(\W)+/", "-", strtolower($model->name));
            $model->store_id   = CurrentStore::getStoreId();
            $model->sort_order = 0;
            $model->created_at = time();

            if ($model->save()) {
                if (CurrentStore::isNone()) {
                    $brandStore = new CatalogBrandStore();
                    $brandStore->brand_id = $model->id;
                    $brandStore->store_id = CurrentStore::getStoreId();
                    $brandStore->save();
                }
                return $this->redirect('index');
            } else {
                error_log(print_r($model->getErrors(), true), 0);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatalogBrand model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        $model = $this->findModel($id);
        $current = CatalogBrand::findOne($id);

        if ($data = Yii::$app->request->post()) {

            $model->load($data);

            //$model->masthead_image = UploadedFile::getInstance($model, 'masthead_image');
            $model->logo_color     = UploadedFile::getInstance($model, 'logo_color');

            if (isset($model->logo_color) && !empty($model->logo_color)) {
                $filename = '/brands/logos/' . $model->slug . '_color.' . $model->logo_color->extension;
                $location = Yii::getAlias("@frontend") . '/web/uploads';
                if (!empty($model->logo_color) && $model->logo_color->saveAs($location . $filename)) {
                    $model->logo_color = $filename;
                } else {
                    return false;
                }
            }else{
                $model->logo_color = $current->logo_color;
            }

            $model->save();

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionCarry() {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        if (Yii::$app->request->isPost) {
            $bid    = isset($_POST['bid']) ? $_POST['bid'] : false;
            $action = isset($_POST['action']) ? $_POST['action'] : false;

            if ($bid && $action) {
                $brandStore = CatalogBrandStore::findOne([
                    'brand_id' => $bid, 'store_id' => CurrentStore::getStoreId()
                ]);
                switch ($action) {
                    case 'carry':
                        if (empty($brandStore)) {
                            $brandStore             = new CatalogBrandStore();
                            $brandStore->brand_id   = $bid;
                            $brandStore->store_id   = CurrentStore::getStoreId();
                            $brandStore->created_at = time();
                            if ($brandStore->save())
                                return true;
                        }
                        break;
                    case 'remove':
                        if (!empty($brandStore)) {
                            if ($brandStore->delete())
                                return true;
                        }
                        break;
                }
            }
        }

        return false;
    }

    /**
     * Deletes an existing CatalogBrand model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to delete brands.");

        if (CatalogProduct::deleteProductByBrand($id)) {
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    public function actionSave()
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage brands.");

        $oldBrands = CatalogStorebrands::find()->all();

        foreach($oldBrands as $oBrands){
            $brandIds = explode(',',$oBrands->brands_ids);
            foreach($brandIds as $id){
                //echo $id; die;
                $newBrand = CatalogBrand::find()->where(['manufacturer_id' => $id])->one();

                if(!$newBrand){
                    continue;
                }
                $catalogBrandStore = new CatalogBrandStore();
                $catalogBrandStore->brand_id = $newBrand->id;
                $catalogBrandStore->store_id = $oBrands->store_id;
                $catalogBrandStore->created_at = time();
//
//                echo $newBrand->id ."<br/><br/>";
//
                $catalogBrandStore->save(false);

            }
        }

    }

    /**
     * Finds the CatalogBrand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogBrand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogBrand::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
