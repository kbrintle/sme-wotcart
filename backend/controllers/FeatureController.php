<?php

namespace backend\controllers;

use Yii;
use common\models\catalog\CatalogFeature;
use common\models\catalog\search\CatalogFeatureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CurrentStore;

/**
 * FeatureController implements the CRUD actions for CatalogProductFeature model.
 */
class FeatureController extends Controller
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

    /**
     * Lists all CatalogProductFeature models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (CurrentStore::isNone()) {
            $features = CatalogFeature::findAll(['store_id' => 0, 'is_active' => true, 'is_deleted' => false]);
        } else {
            $features = CatalogFeature::find()
                ->where('store_id = 0')
                ->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()])
                ->andWhere('is_active = :is_active', [':is_active' => true])
                ->andWhere('is_deleted = :is_deleted', [':is_deleted' => false])
                ->all();
        }

        return $this->render('index', [
            'features' => $features,
        ]);
    }

    /**
     * Displays a single CatalogProductFeature model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CatalogProductFeature model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogFeature();

        if ($model->load(Yii::$app->request->post())) {
            $model->store_id = CurrentStore::getStoreId();
            $model->created_at = time();

            if ($model->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatalogProductFeature model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CatalogProductFeature model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogProductFeature model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProductFeature the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogFeature::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
