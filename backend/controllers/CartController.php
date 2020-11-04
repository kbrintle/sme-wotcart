<?php

namespace backend\controllers;

use common\models\store\Cart;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller{

    public function actionIndex(){
        $carts = Cart::find()->mine()->all();

        return $this->render('index',[
            'carts' => $carts
        ]);
    }


    public function actionView($id){
        $model = Cart::findOne($id);

        return $this->render('view',[
            'model' => $model
        ]);
    }


    public function actionUpdate($id){
        $model = Cart::findOne($id);

        return $this->render('update', [
            'model' => $model
        ]);
    }

}