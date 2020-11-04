<?php

namespace backend\controllers;

use Yii;
use common\models\store\StoreCoupon;
use common\models\store\search\StoreCouponSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CurrentStore;
use backend\models\UploadForm;
use yii\web\UploadedFile;

/**
 * CouponController implements the CRUD actions for StoreCoupon model.
 */
class CouponController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all StoreCoupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoreCouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $coupons = StoreCoupon::findAll([
            'store_id'   => CurrentStore::getStoreId(),
            'is_active'  => true,
            'is_deleted' => false
        ]);

        return $this->render('index', [
            'coupons'      => $coupons,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreCoupon model.
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
     * Creates a new StoreCoupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model  = new StoreCoupon();
        $upload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file = $upload->uploadCoupon();

                $model->store_id   = CurrentStore::getStoreId();
                $model->image      = $file;
                $model->is_deleted = 0;
                $model->created_at = time();

                if ($model->save())
                    return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model'  => $model,
                'upload' => $upload
            ]);
        }
    }

    /**
     * Updates an existing StoreCoupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model  = $this->findModel($id);
        $upload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file = $upload->uploadCoupon();

                if ($model->image)
                    unlink(Yii::getAlias('@root') . '/frontend/web/uploads/coupons/' . $model->image);

                $model->image       = $file;
                $model->modified_at = time();

                if ($model->save())
                    return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model'  => $model,
                'upload' => $upload
            ]);
        }
    }

    /**
     * Deletes an existing StoreCoupon model.
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
     * Finds the StoreCoupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreCoupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreCoupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
