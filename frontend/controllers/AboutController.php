<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\settings\SettingsStore;

class AboutController extends Controller
{

    public function actionIndex(){
        $this->view->title = "About";
        return $this->render('index', [
            'model' => SettingsStore::find()->one()
        ]);
    }
}