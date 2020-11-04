<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use common\models\core\Store;
use common\models\store\StoreFinancing;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use common\models\store\StoreEvent;

class EventsController extends Controller
{

    public function actionIndex(){

        $this->view->title = "Events";
        $model = StoreEvent::find()->where([
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
            'is_active' => true,
        ])
            ->orderby('event_date')
            ->all();

        if(!$model){
            throw new \yii\web\NotFoundHttpException();
        }


        return $this->render('/events/list', [
            'events' => $model
        ]);
    }

    public function actionDetail($slug=null){

        $model = StoreEvent::find()->where([
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'slug'     => $slug
        ]
        )->one();

        if(!$model){
            throw new \yii\web\NotFoundHttpException();
        }
        $this->view->title = "Events - ". $model->title;




        return $this->render('/events/detail', [
            'event' => $model
        ]);
    }
}