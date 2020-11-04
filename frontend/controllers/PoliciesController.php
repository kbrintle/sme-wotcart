<?php

namespace frontend\controllers;

use common\components\CurrentStore;
use common\models\store\StoreBenefitPage;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use common\models\store\StoreLocation;
use frontend\models\StoreContactForm;



class PoliciesController extends Controller
{

    public function actionIndex($zip_code=null){

        $this->view->title = CurrentStore::getStore()->name . " Store Policies";

        $locations = StoreBenefitPage::find()->where(['store_id'=>CurrentStore::getStore()->id])
            ->one();

        return $this->render('index', [
            'policies' => $locations,
        ]);
    }



}