<?php

namespace backend\controllers;

use backend\models\AttributesetOrder;
use Yii;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSetAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\components\CurrentStore;
use common\models\core\Admin;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\core\Store;
use common\components\helpers\PermissionHelper;


/**
 * AttributesetController implements the CRUD actions for AttributeSet model.
 */
class AttributesetController extends Controller
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
                    'delete'    => ['POST'],
                    'order'     => ['POST']
                ],
            ],
        ];
    }

    /**
     * Lists all AttributeSet models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (CurrentStore::isNone()) {
            $attributeSets = CatalogAttributeSet::findAll(['store_id' => 0, 'is_active' => true, 'is_deleted' => false]);
        } else {
            $attributeSets = CatalogAttributeSet::find()
                ->where('store_id = 0')
                ->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()])
                ->andWhere('is_active = :is_active', [':is_active' => true])
                ->andWhere('is_deleted = :is_deleted', [':is_deleted' => false])
                ->all();
        }

        return $this->render('index', [
            'attributeSets' => $attributeSets,
        ]);
    }

    /**
     * Creates a new AttributeSet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(){
        $model = new CatalogAttributeSet();

        if( $model->load(Yii::$app->request->post()) ){
            $model->slug       = str_replace(' ', '-', strtolower($model->label));
            $model->store_id   = CurrentStore::getStoreId();
            $model->created_at = time();

            if( $model->save() ){
                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AttributeSet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byStore($model, "Sorry, you don't have permission to update this attribute set.");

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSelect($id, $aid = 0, $action = false)
    {
        if ($id && $action && $aid) {
            $model = $this->findModel($id);

            if ($model)
                PermissionHelper::byStore($model, "Sorry, you don't have permission to update this attribute set.");

            $attributeSetAttribute = CatalogAttributeSetAttribute::findOne([
                'set_id' => $id, 'attribute_id' => $aid
            ]);

            switch ($action) {
                case 'select':
                    if (empty($attributeSetAttribute)) {
                        $attributeSetAttribute                  = new CatalogAttributeSetAttribute();
                        $attributeSetAttribute->set_id          = $id;
                        $attributeSetAttribute->attribute_id    = $aid;
                        $attributeSetAttribute->set_category_id = 1;
                        $attributeSetAttribute->created_at      = time();
                        $attributeSetAttribute->modified_at     = time();
                        $attributeSetAttribute->save();
                    }
                    break;
                case 'remove':
                    if (!empty($attributeSetAttribute)) {
                        $attributeSetAttribute->delete();
                    }
                    break;
            }
        }

        return $this->redirect(['attributes', 'id' => $id]);
    }

    public function actionAttributes($id) {
        $attributes = CatalogAttribute::find()
            ->where('store_id = 0')
            ->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()])
            ->andWhere('is_active = :is_active', [':is_active' => true])
            ->andWhere('is_deleted = :is_deleted', [':is_deleted' => false])
            ->andWhere('is_default = :is_default', [':is_default' => false])
            ->all();
        $defaultAttributes = CatalogAttribute::find()->where([
            'store_id' => 0,
            'is_active' => true,
            'is_deleted' => false,
            'is_default' => true
        ])->all();
        $selectedAttributes = CatalogAttributeSetAttribute::find()->where(['is_deleted'=>false, 'set_id' => $id])->all();

        //compare Available to Selected attributes
        $available  = ArrayHelper::getColumn($attributes, 'id');
        $selected   = ArrayHelper::getColumn($selectedAttributes, 'attribute_id');
        $available  = array_diff($available, $selected);

        //get the actual CatalogAttribute records
        $available = CatalogAttribute::findAll($available);
        $selected  = CatalogAttribute::findAll($selected);

        $all_attributes = array_merge($defaultAttributes, $selected);
        $all_attributes = AttributesetOrder::sortAttributes($all_attributes);

        return $this->render('attributes', [
            'attributeSetId'        => $id,
            'defaultAttributes'     => $defaultAttributes,
            'availableAttributes'   => $available,
            'selectedAttributes'    => $selected,
            'allAttributes'         => $all_attributes
        ]);
    }

    /**
     * Deletes an existing AttributeSet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model)
            PermissionHelper::byStore($model, "Sorry, you don't have permission to remove this attribute set.");

        $model->delete();
        return $this->redirect(['index']);
    }


    public function actionOrder(){
        $order_data = Yii::$app->request->post('order');
        AttributesetOrder::update($order_data);
    }

    /**
     * Finds the AttributeSet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogAttributeSet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogAttributeSet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
