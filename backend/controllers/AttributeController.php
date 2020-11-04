<?php

namespace backend\controllers;

use Yii;
use backend\models\AttributeForm;
use common\components\CurrentStore;
use common\models\catalog\CatalogAttributeType;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeOption;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\core\Store;
use common\components\helpers\PermissionHelper;

/**
 * AttributeController implements the CRUD actions for CatalogAttribute model.
 */
class AttributeController extends Controller
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
        if (CurrentStore::isNone()) {
            $attributes = CatalogAttribute::findAll(['store_id' => 0, 'is_active' => true, 'is_deleted' => false]);
        } else {
            $attributes = CatalogAttribute::find()
                ->where('store_id = 0')
                ->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()])
                ->andWhere('is_active = :is_active', [':is_active' => true])
                ->andWhere('is_deleted = :is_deleted', [':is_deleted' => false])
                ->all();
        }

        return $this->render('index', [
            'attributes' => $attributes,
        ]);
    }

    /**
     * Displays a single CatalogAttribute model.
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
     * Creates a new CatalogAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        $model = new AttributeForm();                       //form model (process in middleware)

        if( $model->load(Yii::$app->request->post()) ){     //load form model
            if( $model->validate() ){                       //validate form model
                if( $model->save(true) ){                   //save form model
                    return $this->redirect('index');
                }
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing CatalogAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $catalog_attribute = $this->findModel($id);

        if ($catalog_attribute)
            PermissionHelper::byStore($catalog_attribute, "Sorry, you don't have permission to update this attribute.");

        $model = new AttributeForm();                       //form model (process in middleware)
        $model->loadAttribute($catalog_attribute);          //load CatalogAttribute to form model

        if( $model->load(Yii::$app->request->post()) ){//load form model
            $model->is_product_view = $_POST['AttributeForm']['is_product_view'];
            $model->product_view_sort = $_POST['AttributeForm']['product_view_sort'];
            if( $model->validate() ){                       //validate form model
                if( $model->save() ){                   //save form model
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [ 'model' => $model]);
    }

    /**
     * Deletes an existing CatalogAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byStore($model, "Sorry, you don't have permission to remove this attribute.");

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
