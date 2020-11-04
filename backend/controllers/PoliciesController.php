<?php

namespace backend\controllers;

use Yii;
use common\components\CurrentStore;
use common\models\store\StoreBenefitPage;
use yii\web\Controller;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BenefitsPageController implements the CRUD actions for StoreBenefit model.
 */
class PoliciesController extends Controller
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

    public function actionIndex(){

        $model = StoreBenefitPage::find()->where(['store_id'=>CurrentStore::getStoreId()])->one();

        if(!$model){
            return $this->redirect(['create']);
        }else{
            return $this->redirect(['update']);
        }
    }

    /**
     * Creates a new StoreBenefit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = StoreBenefitPage::find()->where(['store_id'=>CurrentStore::getStoreId()])->one();

        if($model){
            return $this->redirect(['update']);
        }

        $model = new StoreBenefitPage();

        if ($model->load(Yii::$app->request->post())) {
            $model->store_id = CurrentStore::getStoreId();
            $model->created_at = time();

            if ($model->save()) {
                return $this->redirect('update');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreBenefit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id=null)
    {

        $model = StoreBenefitPage::find()->where(['store_id'=>CurrentStore::getStoreId()])->one();

        if(!$model){
            return $this->redirect(['create']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->modified_at = time();

            if ($model->save()) {
                return $this->redirect(['update']);
            }
        }else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Finds the StoreBenefit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreBenefit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreBenefitPage::find(['id'=>$id])->one() !== null)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}