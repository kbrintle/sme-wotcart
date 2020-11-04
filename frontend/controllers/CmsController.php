<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use common\models\cms\CmsPage;
use Yii;
use yii\web\Controller;
use yii\helpers\VarDumper;

class CmsController extends Controller{

    public function actionView($url_key){
        $url = $url_key;
        if(strpos($url_key, '?') == TRUE){
            $url = explode('?', $url_key);
            $url = $url[0];
        }

        $model = CmsPage::getCmsPage($url);

        ///VarDumper::dump($model, 4, 1); die;

        if($model){
            return $this->render('view',[
                'model' => $model
            ]);
        }
        throw new \yii\web\NotFoundHttpException("Page not found");
    }

}