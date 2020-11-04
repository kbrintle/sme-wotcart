<?php

namespace backend\controllers;

use backend\components\CurrentUser;
use common\components\CurrentStore;
use common\components\helpers\FormHelper;
use common\components\helpers\PermissionHelper;
use common\models\cms\CmsPage;
use common\models\cms\search\CmsPageSearch;
use common\models\core\Store;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class CmsController extends Controller{
    public $enableCsrfValidation = false;

    public function behaviors(){
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
//                    'upload' => ['post', 'put'],
                ],
            ],
        ];
    }

    public function beforeAction($action){
        if( $action->id == 'upload' ){
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex(){
        $searchModel = new CmsPageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionUpdate($id){
        $model = CmsPage::findOne($id);

        if ($model)
            PermissionHelper::byColumn($model->author_id, CurrentUser::getUserId(), "Sorry, you don't have permission to update this CMS page.");

        $model->modified_time = time();

        /**
         * Save the model
         */
        if( $model->load(Yii::$app->request->post()) ){
            if( $model->validate() ){
                $model->save();
                return $this->redirect(['index']);
            }
        }

        return $this->render('update',[
            'model' => $model
        ]);
    }

    public function actionCreate(){
        $model                  = new CmsPage();
        $model->author_id       = Yii::$app->user->id;
        $model->created_time    = time();

        /**
         * Save the model
         */
        if( $model->load(Yii::$app->request->post()) ){
            $model->url_key = FormHelper::getFormattedURLKey($model->url_key);
            if( $model->validate() ){
                $model->save();

                //store relation
                $store_id = CurrentStore::getStoreId();
                $model->createStoreRelationship($store_id);

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionDelete($id){
        $model = CmsPage::findOne($id);

        if ($model)
            PermissionHelper::byColumn($model->author_id, CurrentUser::getUserId(), "Sorry, you don't have permission to remove this CMS page.");

        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionUpload(){
        $accepted_origins = array("http://localhost", "http://192.168.1.1", "http://am-retail.dev");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = Yii::getAlias("@frontend") . '/web/uploads/cms/';

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

//            // Sanitize input
//            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
//                header("HTTP/1.0 500 Invalid file name.");
//                return;
//            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.0 500 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin

            //remove spaces
            $temp['name'] = preg_replace('/\s+/', '_', $temp['name']);

            $filetowrite = $imageFolder . $temp['name'];
//            echo "file =".$filetowrite;
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
//            $return_location = Url::to('/admin/cms/'.$temp['name']);
            //print_r(array('location' => $temp['name'])); die;
            echo json_encode(array('location' => $temp['name']));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.0 500 Server Error");
        }

        return;
    }
}