<?php

namespace frontend\controllers;

use common\models\core\Store;
use common\models\WizardQuestions;
use common\models\WizardResponses;
use frontend\models\ZipLookupForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class WizardController extends Controller{

    public function behaviors(){
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'get'       => ['get'],
                    'process'   => ['post']
                ],
            ],
        ];
    }

    /**
     * Displays index page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays recommendations page.
     *
     * @return mixed
     */
    public function actionRecommendations(){
        $session = Yii::$app->session;

        if(Yii::$app->request->isAjax){
            $data = file_get_contents("php://input");
            $data = json_decode($data, TRUE);

            //find nearest store by zip code
            $zip_lookup         = new ZipLookupForm();
            $zip_lookup->zip    = $data['zip_code'];
            $closest_store      = $zip_lookup->findClosestStore();

            //save response
            $wizard_response            = new WizardResponses();
            $wizard_response->email     = $data['email'];
            $wizard_response->zip_code  = $data['zip_code'];
            $wizard_response->steps     = Json::encode($data['steps']);
            $wizard_response->store_id  = $closest_store->id;
            $wizard_response->save();

            //setting the recommendedStore as a session var because GET does not work
            $session->set('recommendedSteps', $data['steps']);
            $session->set('recommendedStore', $closest_store->id);

            return $closest_store->id;
        }

        if( $session->get('recommendedStore') ){
            $store_id   = $session->get('recommendedStore');
            $store      = Store::findOne($store_id);

            //do store filter here
            // @NOTE: Mapping products depends on us 1) building out the catalog a bit further and 2) getting client feedback

            return $this->render('recommendations', [
                'store' => $store
            ]);
        }

        return false;
    }


    public function actionAll(){
        if(Yii::$app->request->isAjax){
            $models = WizardQuestions::find()->all();

            $output = [];
            foreach($models as $model){
                $temp_arr = ArrayHelper::toArray($model);
                $temp_arr['options'] = $model->wizardOptions;
                array_push($output, $temp_arr);
            }

            return Json::encode($output);
        }
    }

    public function actionProcess(){
        if(Yii::$app->request->isAjax){
            $data = file_get_contents("php://input");
            $data = json_decode($data, TRUE);

            //find nearest store by zip code
            $zip_lookup         = new ZipLookupForm();
            $zip_lookup->zip    = $data['zip_code'];
            $closest_store      = $zip_lookup->findClosestStore();

            //save response
            $wizard_response            = new WizardResponses();
            $wizard_response->email     = $data['email'];
            $wizard_response->zip_code  = $data['zip_code'];
            $wizard_response->steps     = Json::encode($data['steps']);
            $wizard_response->store_id  = $closest_store->id;
            $wizard_response->save();

            //do store filter here
            // @NOTE: Mapping products depends on us 1) building out the catalog a bit further and 2) getting client feedback

            return true;
        }
    }
}