<?php

namespace backend\controllers;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttachment;
use common\models\catalog\CatalogProductAttachment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Yii;
use yii\helpers\Json;
use common\components\helpers\PermissionHelper;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * AttributeController implements the CRUD actions for CatalogAttribute model.
 */
class AttachmentController extends Controller
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
     * Lists all CatalogAttribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post('hasEditable')) { //AJAX
            $attachmentId = Yii::$app->request->post('editableKey');
            $model = CatalogAttachment::findOne($attachmentId);

            $message = "error";
            $output = 'error';

            $posted = current($_POST['CatalogAttachment']);
            $post = ['CatalogAttachment' => $posted];

            if ($model->load($post)) {
                $model->modified_at = time();
                $model->save();
                $message = '';
                $output = '';
            }
            // return ajax json encoded response and exit
            echo Json::encode(['output' => $output, 'message' => $message]);;
            return;
        }

        $query = CatalogAttachment::find();
        $query->where('store_id = 0');
        if (!CurrentStore::isNone()) {
            $query->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()]);
        }
        //$query->andWhere('is_active = :is_active', [':is_active' => true]);
        $query->andWhere('is_deleted = :is_deleted', [':is_deleted' => false]);

        $attachmentProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'attachmentCount' => $query->count(),
            'attachmentProvider' => $attachmentProvider,
            //'gridColumns' => $gridColumns
        ]);
    }

    /**
     * Creates a new CatalogAttachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogAttachment();
        $store_id = (CurrentStore::getStoreId()) ? CurrentStore::getStoreId() : 0;//form model (process in middleware)

        if ($model->load(Yii::$app->request->post())) {

            $directory = Yii::getAlias('@frontend/web/uploads/attachments') . DIRECTORY_SEPARATOR;
            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }

            $imageFile = UploadedFile::getInstance($model, 'file_name');

            //var_dump($imageFile,$_POST,$model);die;
            if ($imageFile) {
                $fileName = str_replace(' ', '', $imageFile->name);
                $filePath = $directory . $fileName;

                if ($imageFile->saveAs($filePath, true)) {
                    $model->file_name = "/attachments/$fileName";
                    $model->file_type = pathinfo($filePath, PATHINFO_EXTENSION);
                    $model->created_at = time();
                    $model->is_active = 1;
                    $model->is_deleted = 0;
                    $model->store_id = $store_id;
                    if ($model->save(true)) {                   //save form model
                        return $this->redirect("update/$model->attachment_id");
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'isCreate' => true,
            'attachmentProducts' => []

        ]);
    }

    /**
     * Updates an existing CatalogAttachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $store_id = (CurrentStore::getStoreId()) ? CurrentStore::getStoreId() : 0;
        $model = $this->findModel($id);

        if ($model) {
            PermissionHelper::byStore($model, "Sorry, you don't have permission to update this attachment.");
        }

        if ($post = Yii::$app->request->post()) {
            $oldFileName = $model->file_name;
            $oldFile = Yii::getAlias("@frontend/web/uploads/$model->file_name");

            if ($model->load(Yii::$app->request->post())) {

                $directory = Yii::getAlias('@frontend/web/uploads/attachments/');
                if (!is_dir($directory)) {
                    FileHelper::createDirectory($directory);
                }

                $imageFile = UploadedFile::getInstance($model, 'file_name');
                if ($imageFile) {
                    $fileName = str_replace(' ', '', $imageFile->name);
                    $filePath = $directory . $fileName;

                    if ($imageFile->saveAs($filePath, true)) {
                        $model->file_name = "/attachments/$fileName";
                        $model->file_type = pathinfo($filePath, PATHINFO_EXTENSION);
                        $model->created_at = time();
                        $model->is_active = 1;
                        $model->is_deleted = 0;
                        $model->store_id = $store_id;
                        if ($model->save(true)) {                   //save form model
                            if (is_file($oldFile)) {
                                unlink($oldFile);
                            }
                            Yii::$app->session->setFlash('success', 'Update Successful');
                        }
                    }
                } else {
                    $model->file_name = $oldFileName;
                    $model->store_id = $store_id;
                    if ($model->save(true)) {                   //save form model
                        Yii::$app->session->setFlash('success', 'Update Successful');
                    }
                }
            }
        }

        $attachmentProducts = [];
        $products = CatalogProductAttachment::find()->Where(["attachment_id" => $model->attachment_id])->all();
        if (!empty($products)) {
            foreach ($products as $product) {
                $attachmentProducts[] = $product['product_id'];
            }
        }
        return $this->render('update', [
                'model' => $model,
                'attachmentProducts' => $attachmentProducts,
                'isUpdate' => true
            ]
        );
    }

    /**
     * Deletes an existing Catalogattachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            PermissionHelper::byStore($model, "Sorry, you don't have permission to remove this attachment.");
            $model->delete();
            $file = Yii::getAlias("@frontend/web/uploads/$model->file_name");
            if (is_file($file)) {
                unlink($file);
            }
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = CatalogAttachment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}