<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use common\models\store\StoreFinancing;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use common\models\store\StoreNewsletterSubscriber;
use common\components\Notify;

class FinanceController extends Controller
{

    public function actionIndex(){
        $this->view->title = "Finance";
        $model = StoreFinancing::find()->where([
            'store_id' => CurrentStore::getStoreId()]
        )->one();

        if(!$model){
            throw new \yii\web\NotFoundHttpException();
        }


        return $this->render('/finance/index', [
            'financing' => $model
        ]);
    }
}