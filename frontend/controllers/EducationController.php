<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Location;

class EducationController extends Controller
{

    public function actionIndex()
    {
        $this->view->title = "Education";
        return $this->render('index');
    }


}