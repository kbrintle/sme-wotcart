<?php

namespace backend\controllers;

use backend\components\CurrentUser;
use common\components\CurrentStore;
use Yii;
use common\models\store\StoreEvent;
use common\models\store\search\StoreEventSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\components\helpers\FormHelper;

/**
 * EventController implements the CRUD actions for StoreEvent model.
 */
class EventController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all StoreEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreEvent model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StoreEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StoreEvent();
        $model->event_start_date = date("Y-m-d H:i:s", time());
        $model->event_end_date = date("Y-m-d H:i:s", time());


        if($data = Yii::$app->request->post()){
            $model->load($data);

            $model->created_at  = time();
            $model->modified_at = time();
            $model->event_start_date = date("Y-m-d H:i:s", strtotime($data['StoreEvent']['event_start_date']));
            $model->event_end_date = date("Y-m-d H:i:s", strtotime($data['StoreEvent']['event_end_date']));
            $model->slug        = FormHelper::getFormattedURLKey($model->title);
            $model->author_id   = CurrentUser::getUserId();
            $model->is_active   = 1;
            $model->is_deleted  = 0;

            $model->store_id  = CurrentStore::getStoreId();

            $model->featured_image = UploadedFile::getInstance($model, "featured_image");
            if( $model->remove_featured_image ){
                $model->featured_image_path = null;
            }else{
                if( $model->featured_image ){
                    $upload_dir = '/uploads/events/'.$model->slug.'.'.$model->featured_image->extension;
                    $path = Yii::getAlias("@frontend") . '/web' . $upload_dir;
                    $model->featured_image->saveAs($path);
                    $model->featured_image_path = 'uploads/events/'.$model->slug.'.'.$model->featured_image->extension;
                    $model->featured_image = null;  //need to do this to trick the model into saving, otherwise it will try to save an object
                }
            }

            if($model->save()) {
                return $this->redirect(['index']);
            }
        }

        $model->event_date = date('Y-m-d h:m:i', time());
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($data = Yii::$app->request->post()) {
            $model->load($data);
            //print_r($data); die;
            $model->modified_at = time();
            //print_r(strtotime($data['StoreEvent']['event_start_date'])); die;
            $model->event_start_date = date("Y-m-d H:i:s", strtotime($data['StoreEvent']['event_start_date']));
            $model->event_end_date = date("Y-m-d H:i:s", strtotime($data['StoreEvent']['event_end_date']));
            $model->author_id = CurrentUser::getUserId();
            $model->store_id = CurrentStore::getStoreId();
//print_r($model); die;
            $model->featured_image = UploadedFile::getInstance($model, "featured_image");
            if ($data['StoreEvent']['remove_featured_image'] === '1') {
                $model->featured_image_path = null;
            } else {
                if ($model->featured_image) {
                    $filename = preg_replace('/[^a-z0-9]+/', '_', strtolower($model->title));
                    $upload_dir = '/uploads/events/' . $filename . '.' . $model->featured_image->extension;
                    $path = Yii::getAlias("@frontend") . '/web' . $upload_dir;
                    $model->featured_image->saveAs($path);
                    $model->featured_image_path = "uploads/events/$filename." . $model->featured_image->extension;
                    $model->featured_image = null;  //need to do this to trick the model into saving, otherwise it will try to save an object
                }
            }

            //print_r($model);

            if ($model->save(false)) {

            } else {
                var_dump($model->errors);
                die;
            }

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StoreEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoreEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreEvent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionUpload(){

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = Yii::getAlias("@frontend") . '/web/uploads/events/';

        reset ($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])){

            if (isset($_SERVER['HTTP_ORIGIN'])) {

                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);

            }

            /*
              If your script needs to receive cookies, set images_upload_credentials : true in
              the configuration and enable the following two headers.
            */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.0 500 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.0 500 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin

            //remove spaces
            $temp['name'] = preg_replace('/\s+/', '_', $temp['name']);

            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $temp['name']));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.0 500 Server Error");
        }

        return;
    }
}
