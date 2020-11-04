<?php

namespace backend\controllers;

use Yii;
use backend\models\UploadForm;
use common\components\CurrentStore;
use common\models\store\StoreBanner;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\settings\SettingsStore;
use common\models\core\CoreConfig;

/**
 * FlyerController implements the CRUD actions for StoreBanner model.
 */
class BannerController extends Controller
{

    public function actionTopNavBanner()
    {
        $settingsStore = SettingsStore::find()->one();

        if (!isset($settingsStore)) {
            $settingsStore = new SettingsStore();
        }

        if ($data = Yii::$app->request->post()) {
            //Load model data
            //var_dump($data);die;
            if (isset($data['SettingsStore']['banner_type'])) {
                CoreConfig::saveConfig('design/nav/banner_type', $data['SettingsStore']['banner_type'], CurrentStore::getStoreId());
            }
            if (isset($data['SettingsStore']['banner_text'])) {
                CoreConfig::saveConfig('design/nav/banner_text', $data['SettingsStore']['banner_text'], CurrentStore::getStoreId());
            }
            if (isset($data['SettingsStore']['banner_url'])) {
                CoreConfig::saveConfig('design/nav/banner_url', $data['SettingsStore']['banner_url'], CurrentStore::getStoreId());
            }

            if (CurrentStore::getStoreId() === '0') {
                Yii::$app->db->createCommand("DELETE FROM core_config WHERE store_id != 0 AND path IN ('design/nav/banner_type','design/nav/banner_text', 'design/nav/banner_url')")->execute();
            }
        }

        $settingsStore->banner_url = CoreConfig::getStoreConfig('design/nav/banner_url');
        $settingsStore->banner_text = CoreConfig::getStoreConfig('design/nav/banner_text');
        $settingsStore->banner_type = CoreConfig::getStoreConfig('design/nav/banner_type');
        $settingsStore->save(false);

        return $this->render('top-nav-banner', [
            'model' => $settingsStore
        ]);
    }

    /**
     * Lists all StoreBanner models.
     * @return mixed
     */
    public function actionHomePage()
    {
        $mastHead = StoreBanner::getBannerByPageLocation("masthead", CurrentStore::getStoreId());
        $leftShop = StoreBanner::getBannerByPageLocation("leftshop", CurrentStore::getStoreId());
        $rightShop = StoreBanner::getBannerByPageLocation("rightshop", CurrentStore::getStoreId());
        $bigShop = StoreBanner::getBannerByPageLocation("bigshop", CurrentStore::getStoreId());

        return $this->render('homepage', [
            'mastHead' => $mastHead,
            'leftShop' => $leftShop,
            'rightShop' => $rightShop,
            'bigShop' => $bigShop
        ]);
    }

    public function actionProductCategory()
    {
        $categories = StoreBanner::getBannerByPageLocation("category", CurrentStore::getStoreId(), true);
        return $this->render('product-category', ['categories' => $categories]);
    }

    public function actionProductDetail()
    {
        $categories = StoreBanner::getBannerByPageLocation("detail", CurrentStore::getStoreId(), true);
        return $this->render('product-detail', ['categories' => $categories]);
    }

    /**
     * Displays a single StoreBanner model.
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
     * Creates a new StoreBanner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StoreBanner();
        $upload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            $post = Yii::$app->request->post();

            if ($upload->file) {
                $file = $upload->uploadBanner();


                $model->store_id = CurrentStore::getStoreId();
//                $model->starts_at  = strtotime($model->starts_at);
//                $model->ends_at  = strtotime($model->ends_at);
                $model->image = $file;
                $model->is_deleted = 0;
                $model->created_at = time();

                if ($model->save())
                    return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload' => $upload
            ]);
        }
    }

    /**
     * Updates an existing StoreBanner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $upload = new UploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

//            $post = Yii::$app->request->post();
//            print_r($post);
//             die;

            if ($upload->file) {
                $file = $upload->uploadBanner();

                if ($model->image)
                    unlink(Yii::getAlias('@root') . '/frontend/web/uploads/banners/' . $model->image);
                $model->image = $file;

            }

//          $model->starts_at  = strtotime($model->starts_at);
//          $model->ends_at  = strtotime($model->ends_at);
            $model->is_active = time();
            $model->modified_at = time();
            if ($model->save())
                return $this->redirect(['index']);


        } else {
            return $this->render('update', [
                'model' => $model,
                'upload' => $upload
            ]);
        }
    }

    /**
     * Deletes an existing StoreBanner model.
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
     * Finds the StoreBanner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreBanner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreBanner::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetImagePreview()
    {
        $model = new StoreBanner();                       //form model (process in middleware)

        if ($model->load(Yii::$app->request->post())) {

            $imageFile = UploadedFile::getInstance($model, 'image');

            if ($imageFile)
                return $imageFile->tempName;
        }
    }

    public function actionAjaxDelete()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['StoreBanner']['id']) && !empty($post['StoreBanner']['id'])) {
                if ($model = StoreBanner::findOne(['id' => $post['StoreBanner']['id']])) {
                    $model->delete();
                }

                $model = new StoreBanner();
                $model->page_location = $post['StoreBanner']["page_location"];
                $categoryModal = "";
                return json_encode(["banner" => $this->renderPartial("partials/_$model->page_location", [
                    'model' => $model
                ]), "modal" => $categoryModal,
                    'action' => 'deleted']);
            }
        }
    }

    public function actionAjaxCreate()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['StoreBanner'])) {
                $model = StoreBanner::findOne(['id' => $post['StoreBanner']['id']]);
                if ($model) {
                    $oldImage = $model->image;
                } else {
                    $model = new StoreBanner();
                    $model->created_at = time();
                    $model->is_active = 1;
                    $model->is_deleted = 0;
                }
                if ($model->load($post)) {
                    $directory = Yii::getAlias('@frontend/web/uploads/banners') . DIRECTORY_SEPARATOR;
                    if (!is_dir($directory)) {
                        FileHelper::createDirectory($directory);
                    }
                    $imageFile = UploadedFile::getInstance($model, 'image');
                    if ($imageFile) {
                        $fileName = str_replace(' ', '', $imageFile->name);
                        $filePath = $directory . $fileName;
                        $frontEndPath = "/uploads/banners/$fileName";

                        if ($imageFile->saveAs($filePath, true)) {
                            $model->image = $frontEndPath;
                            if (isset($oldImage)) {
                                $checkIfBeingUsed = count(StoreBanner::getBannerByImage($oldImage, CurrentStore::getStoreId()));
                                if (!$checkIfBeingUsed > 1) {
                                    $file = Yii::getAlias("@frontend/web$oldImage");
                                    if (is_file($file)) {
                                        unlink($file);
                                    }
                                }
                            }
                        }
                    } else {
                        if (isset($oldImage)) {
                            $model->image = $oldImage;
                        }
                    }
                    $model->store_id = CurrentStore::getStoreId();
                    if ($model->save()) {
                        $categoryModal = "";
                        $newModal = "";

                        if ($model->page_location == "category") {
                            $categoryModal = $this->renderPartial("partials/_category-modal", [
                                'model' => $model
                            ]);

                            $newModal = $this->renderPartial("partials/_new-category-modal", [
                                'model' => new StoreBanner()
                            ]);
                        }

                        if ($model->page_location == "detail") {
                            $categoryModal = $this->renderPartial("partials/_detail-modal", [
                                'model' => $model
                            ]);

                            $newModal = $this->renderPartial("partials/_new-detail-modal", [
                                'model' => new StoreBanner()
                            ]);
                        }

                        return json_encode(["banner" => $this->renderPartial("partials/_$model->page_location", [
                            'model' => $model
                        ]), "modal" => $categoryModal,
                            "newModal" => $newModal,
                            'action' => 'create',
                            'id' => $model->id]);
                    } else {
                        return "Image did not save";
                    }
                } else {
                    return "not loaded";
                }
            } else {
                return "not posted";
            }
        }
    }
}