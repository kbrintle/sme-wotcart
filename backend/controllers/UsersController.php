<?php

namespace backend\controllers;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogProduct;
use Yii;
use common\models\core\Admin;
use common\models\core\search\AdminSearch;
use common\models\core\AdminStore;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\AdminUserForm;
use common\components\helpers\PermissionHelper;
use backend\components\CurrentUser;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class UsersController extends Controller
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
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage users.");

        $searchModel = new AdminSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionFixProducts()
    {
        $attributeValues = CatalogAttributeValue::find()->groupBy(['product_id'])->all();
        foreach ($attributeValues as $attribute) {
            $product = CatalogProduct::find()->where(['product_id' => $attribute->product_id]);
            if (!$product) {
                CatalogAttributeValue::deleteAll('product_id = :id', [':id' => $attribute->product_id]);
            }
        }
    }

    /**
     * Displays a single Admin model.
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
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage users.");

        $model = new AdminUserForm(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->saveAdmin(true)) {
                    $id = Admin::find()->where(["email"=>$model->email])->one()->id;
                    if (isset(Yii::$app->request->post()["storeAssociation"]) && $model->role_id == "2") {
                        AdminStore::deleteAll(["admin_id" => $id]);
                        $newAdminStores = array_values(Yii::$app->request->post()["storeAssociation"]);
                        foreach ($newAdminStores as $newStore) {
                            $newAdminStore = new AdminStore();
                            $newAdminStore->store_id = $newStore;
                            $newAdminStore->admin_id = $id;
                            $newAdminStore->save();
                        }
                    }
                    return $this->redirect(['/users/', 'id' => $id]);
                }
            }
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        PermissionHelper::byUserLevel(CurrentUser::isAdmin(), "Sorry, you don't have permission to manage users.");
        $model = AdminUserForm::fillFormUpdate(Admin::findIdentity($id));
        if($post = Yii::$app->request->post()){
        if ($model->load($post)) {
            if ($model->validate()) {
                if ($model->saveAdmin(false, $id)) {
                    if (isset($post["storeAssociation"]) && $model->role_id == "2") {
                        AdminStore::deleteAll(["admin_id" => $id]);
                        $newAdminStores = array_values($post["storeAssociation"]);
                        foreach ($newAdminStores as $newStore) {
                            $newAdminStore = new AdminStore();
                            $newAdminStore->store_id = $newStore;
                            $newAdminStore->admin_id = $id;
                            $newAdminStore->save();
                        }
                    }
                }
                    if ($model->role_id == "1") {
                        AdminStore::deleteAll(["admin_id" => $id]);
                    }
                    return $this->redirect(['/users/', 'id' => $model->id]);
                }
            }
        }
        $model->isNewRecord = false;
        return $this->render('update', [
            'model' => $model,
            'id' => $id
        ]);
    }

    /**
     * Deletes an existing Admin model.
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
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}