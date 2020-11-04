<?php

namespace backend\controllers;

use app\models\Store;
use backend\components\CurrentUser;
use common\components\CurrentStore;
use Yii;
use common\models\store\StoreZipCode;
use common\models\store\search\StoreZipCodeSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\UploadForm;
use yii\web\UploadedFile;


/**
 * StoreZipController implements the CRUD actions for StoreZipCode model.
 */
class ZipcodeController extends Controller
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
     * Lists all StoreZipCode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new StoreZipCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=50;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel
        ]);
    }

    /**
     * Displays a single StoreZipCode model.
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
     * Creates a new StoreZipCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StoreZipCode();

        if ($model->load(Yii::$app->request->post())) {

            if (CurrentStore::getStore()->id != 0){
                $model->store_id = CurrentStore::getStore()->id;
            }
            try {
                if($model->save()){
                    return $this->redirect(['index']);
                }else{
                    throw new \yii\web\HttpException(500, 'Zip Code May Already Exist.');
                }
            } catch (\yii\db\Exception $e) {
                throw new \yii\web\HttpException(500, 'Zip Code May Already Exist.');
            }


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing StoreZipCode model.
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
     * Deletes an existing StoreZipCode model.
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
     * Finds the StoreZipCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreZipCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreZipCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport()
    {
        $model = new UploadForm();

        if ($model->load(Yii::$app->request->post()) ) {

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file) {
                $file = $model->uploadZip();

                $handle = fopen($file, "r");
                Yii::$app->db->createCommand()->truncateTable('store_zip_code')->execute();
                while (($fileop = fgetcsv($handle, 1000, ",")) !== false) {
                    $store = \common\models\core\Store::find()->where([
                        'url' => strtolower($fileop[1]),
                        'is_deleted' => false
                    ])->one();
                    if($store){
                        if(CurrentUser::isAdmin()){
                           $this->saveZip($fileop,  $store);
                        }else{
                            if(CurrentStore::getStoreId() == $store->id){
                                $this->saveZip($fileop, $store);
                            }else{
                                echo "You don't have permissions to upload zips for store ". $store->name;
                            }
                        }

                    }
                }
            }
        }
        return $this->render('import', [
            'model' => $model,
        ]);
    }

    public function actionExport()
    {
        $data = "Zip,Store"."\r\n";
        $model = StoreZipCode::find()->all();
        foreach ($model as $value) {
            $store = \common\models\core\Store::findOne($value->store_id);
            $data .= $value->zip_code.
                ',' . $store->name .
                "\r\n";


        }
        return Yii::$app->response->sendContentAsFile($data, "AM-export-zips.csv", ['mimeType' => 'text/csv']);
    }

    public function saveZip($fileop, $store)
    {

            $zip =  new StoreZipCode();
            $zip->zip_code = $fileop[0];
            $zip->admin_id = CurrentUser::getUserId();
            $zip->store_id = $store->id;
            $zip->status = 1;
            if($zip->save(false)){

            }

    }

}