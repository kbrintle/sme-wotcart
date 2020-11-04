<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use common\models\store\StoreLocation;
use frontend\models\StoreContactForm;



class LocationsController extends Controller
{

    public function actionIndex($zip_code=null){

        $this->view->title = CurrentStore::getStore()->name . " Locations";

        $locations = StoreLocation::find()
            ->store()
            ->orderBy('id')
            ->all();

        if(!$locations || empty($locations)){
            throw new \yii\web\HttpException(404, 'No Locations Found.');
        }

        $model = new StoreContactForm();

        return $this->render('index', [
            'locations' => $locations,
            'model' => $model
        ]);
    }


    public function actionDetail($slug){

        $model = new StoreContactForm();
        $settings = \common\components\CurrentStore::getSettings();

        $location = StoreLocation::find()
            ->where(['slug' => $slug])
            ->orderBy('id')
            ->one();

        $this->view->title = (isset($settings['general']->name))? $settings['general']->name : $location->name." Locations";



        return $this->render('detail', [
            'location' => $location,
            'model' => $model,
        ]);
    }

    public function actionEmail(){

        $model = new StoreContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->sendEmail()){
                echo true;
            }else{
                echo false;
            }

        }else{
           print_r($model->getErrors());
        }
    }
}