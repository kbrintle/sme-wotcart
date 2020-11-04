<?php
namespace backend\controllers;

use backend\models\GoogleAnalytics;
use backend\models\Dashboard;
use mysql_xdevapi\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\components\CurrentUser;


/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DashboardController extends Controller
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
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex(){
        if (CurrentUser::isAdmin()){
            $access_token   = GoogleAnalytics::getAccessToken();
            $ga_view        = GoogleAnalytics::getGAView();
            $dashboard = new Dashboard();
            $sales = $dashboard->getDailyGraphOfSalesBetweenDates('-20 days', 'now');

            return $this->render('index', [
                'sale_graph'  => $sales,
                'access_token'  => $access_token,
                'ga_view'       => $ga_view
            ]);
        }elseif(CurrentUser::isOperations() || CurrentUser::isStoreAdmin()){
            return $this->redirect('orders');
        }
        else{
            throw new \yii\web\NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            Log::record(Log::DOCUMENTS, Log::WARNING, 'The requested page does not exist', 404, true);
        }
    }
}
