<?php

namespace backend\controllers;

use common\models\promotion\PromotionBuyxgety;
use common\models\promotion\PromotionFreeProduct;
use Yii;
use common\models\promotion\PromotionDiscount;
use common\models\promotion\PromotionDiscountCondition;
use common\models\promotion\PromotionPromotion;
use common\models\promotion\PromotionStorePromotion;
use common\models\promotion\search\PromotionPromotionSearch;
use common\models\core\Store;
use common\models\promotion\PromotionStoreCode;
use common\components\CurrentStore;
use backend\models\PromoImagesForm;
use common\models\promotion\PromoImages;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UploadedFile;
use common\models\promotion\search\PromotionCodeSearch;
use common\models\promotion\PromotionCode;
use yii\web\NotFoundHttpException;
use common\models\catalog\CatalogAttribute;
use yii\helpers\ArrayHelper;
use common\models\catalog\CatalogProduct;
use yii\helpers\Json;

class PromotionsController extends Controller
{

    /**
     * Lists all Promotion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = "";
        $dataProvider = "";
        if (CurrentStore::isNone()) {
            $searchModel = new PromotionPromotionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        $badges = [0, 0];

        $enabled = PromotionPromotion::find()
            ->where(['IN', 'id', PromotionStorePromotion::find()
                ->select('promotion_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
            ])->all();
        $available = PromotionPromotion::find()
            ->where([
                'store_id' => [CurrentStore::getStoreId()]
            ])
            ->andWhere(['NOT IN', 'id', $enabled])
            ->all();

        if ($enabled)
            $badges[0] = count($enabled);

        if ($available)
            $badges[1] = count($available);

        return $this->render('index', [
            'enabled' => $enabled,
            'available' => $available,
            'badges' => $badges,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionImages()
    {
        $model = new PromoImagesForm();
        $images = PromoImages::find()->mine(['active' => true])->all();
        $library = PromoImages::find()->library(['active' => false])->all();

        $model->loadImages($images);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                for ($i = 1; $i <= 4; $i++) {
                    $model->{"image_$i"} = UploadedFile::getInstance($model, "image_$i");
                }
                $model->upload();
                $model->save();

                return $this->redirect(['images']);
            } else {
                var_dump($model->errors);
            }
        }

        foreach ($library as $image) {
            if ($image->store_id) {
                $local_img[] = $image;
            } else {
                $global_img[] = $image;
            }
        }

        return $this->render('images', [
            'model' => $model,
            'images' => $images,
            'local_img' => isset($local_img) ? $local_img : false,
            'global_img' => isset($global_img) ? $global_img : false
        ]);
    }

    public function actionUseImage()
    {
        $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : NULL;
        $image_id = $_POST['id'];
        $image_order = $_POST['order'];

        // disable the current image, if one
        $current_image = PromoImages::findOne([
            'store_id' => $store_id,
            'order' => $image_order,
            'active' => true,
        ]);
        if ($current_image) {
            $current_image->order = NULL;
            $current_image->active = 0;
            $current_image->save();
        }

        // if the selected image doesn't belong to
        // the current store, clone it
        $selected_image = PromoImages::findOne($image_id);
        if ($selected_image->store_id != $store_id) {
            $clone = new PromoImages();
            $clone->store_id = $store_id;
            $clone->image = $selected_image->image;
            $clone->link = $selected_image->link;
            $clone->order = $image_order;
            $clone->active = 1;
            $clone->save();
        } else {
            $selected_image->order = $image_order;
            $selected_image->active = 1;
            $selected_image->save();
        }

        return $this->redirect(['images']);
    }

    public function actionEditImage($id = 0)
    {
        if ($id) {
            $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : NULL;

            // if the selected image doesn't belong to
            // the current store, clone it
            $selected_image = PromoImages::findOne($id);
            if ($selected_image->store_id != $store_id) {
                $clone = new PromoImages();
                $clone->store_id = $store_id;
                $clone->image = $selected_image->image;
                $clone->link = $selected_image->link;
                $clone->save();

                $selected_image = $clone;
            }

            if (Yii::$app->request->isPost) {
                $selected_image->load(Yii::$app->request->post());
                $selected_image->save();

                $this->redirect(['images']);
            }

            return $this->render('edit-image', [
                'model' => $selected_image,
            ]);
        }

        return false;
    }

    public function actionDeactivateImage($order = 0)
    {
        if ($order) {
            $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : NULL;

            $selected_image = PromoImages::findOne([
                'store_id' => $store_id,
                'order' => $order,
                'active' => true
            ]);

            $selected_image->active = 0;
            $selected_image->save();

            return $this->redirect(['images']);
        }

        return false;
    }

    public function actionDeleteImage($id = 0)
    {
        if ($id) {
            $image = PromoImages::findOne($id);

            if ($image) {
                $image->delete();
            }

            $this->redirect(['images']);
        }

        return false;
    }

    /**
     * Lists all PromotionCode models.
     * @return mixed
     */
    public function actionCodes()
    {
        $searchModel = new PromotionCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // For store admin index
        if (CurrentStore::isNone()) {
            $codes = PromotionCode::findAll(['is_active' => true, 'is_deleted' => false]);
        } else {
            $codes = PromotionCode::find()
                ->where([
                    'is_active' => true,
                    'is_deleted' => false
                ])
                ->all();
        }

        return $this->render('code/index', [
            'codes' => $codes,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDelete($id = 0)
    {
        PromotionPromotion::deleteAll(["id" => $id]);
        if ($discounts = PromotionDiscount::findAll(["promotion_id" => $id])) {
            foreach ($discounts as $discount) {
                PromotionDiscountCondition::deleteAll(["discount_id" => $discount->id]);
            }
            PromotionDiscount::deleteAll($id);
        }
        PromotionBuyxgety::deleteAll(["promotion_id" => $id]);
        PromotionStorePromotion::deleteAll(["promotion_id" => $id]);
        PromotionDiscount::deleteAll($id);
        $this->redirect(["promotions/"]);
    }

    public function actionDeleteCondition($id = 0, $idd = 0, $cid = 0)
    {
        if ($id && $idd && $cid) {
            $condition = PromotionDiscountCondition::findOne($cid);

            if ($condition->delete())
                $this->redirect(['promotions/discounts',
                    'id' => $id, 'idd' => $idd]);
        } else {
            throw new HttpException(400, 'Invalid or missing ID.');
        }
    }

    public function actionDeleteDiscount($id = 0, $idd = 0)
    {
        if ($id && $idd) {
            if (PromotionDiscount::deleteAll(["id" => $idd]) && PromotionDiscountCondition::deleteAll(["discount_id" => $idd])) {
                $this->redirect(['promotions/']);
            } else {
                throw new HttpException(400, 'Invalid or missing ID.');
            }
        }
    }

    public function actionDiscounts($id = 0, $idd = NULL)
    {
        $promotion = PromotionPromotion::findOne($id);
        if (!empty($idd)) {
            $model = PromotionDiscount::findOne($idd);
        }
        if ($promotion && $promotion->store_id == CurrentStore::getStoreId()) {
            $keys = [
                'sku' => 'SKU',
                'brand' => 'Brand',
                'attribute-set' => 'Attribute Set',
            ];

            if (Yii::$app->request->post()) {
                $isSave = isset($_POST['save']);
                $isRule = isset($_POST['rule']);

                if ($isSave || $isRule) {
                    if (empty($idd)) {
                        $model = new PromotionDiscount();
                        $model->store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : Store::NO_STORE;
                        $model->promotion_id = $id;
                        $model->label = Yii::$app->request->post('label');
                        $model->amount = Yii::$app->request->post('amount');
                        $model->type = Yii::$app->request->post('type');
                        $model->created_at = time();
                        $model->is_active = true;
                    } else {
                        $model->promotion_id = $id;
                        $model->label = Yii::$app->request->post('label');
                        $model->amount = Yii::$app->request->post('amount');
                        $model->type = Yii::$app->request->post('type');
                        $model->modified_at = time();
                    }
                    if ($model->save()) {
                        $model->refresh();
                        $idd = $model->id;
                    } else {
                        throw new HttpException(400, "Unable to create new discount.");
                    }
                }

                if ($isRule && isset($model)) {
                    if (!empty(Yii::$app->request->post('value'))) {
                        $condition = new PromotionDiscountCondition();
                        $condition->discount_id = $model->id ? $model->id : $id;
                        $condition->condition = Yii::$app->request->post('condition');
                        $condition->key = Yii::$app->request->post('key');
                        $condition->operation = Yii::$app->request->post('operation');
                        $condition->value = Yii::$app->request->post('value');
                        $condition->created_at = time();
                        $condition->is_active = true;
                        if (!$condition->save()) {
                            throw new HttpException(400, "Unable to create new discount condition.");
                        }
                    }
                }

                if ($isRule) {
                    $this->redirect(["promotions/discounts/$id?idd=$idd"]);
                } else {
                    $this->redirect(["promotions/"]);
                }
            }

            $conditions = PromotionDiscountCondition::find()->where(['=', 'discount_id', $idd])->all();

            return $this->render('discount/index', [
                'model' => isset($model) ? $model : false,
                'id' => $id,
                'idd' => $idd,
                'keys' => $keys,
                'conditions' => $conditions
            ]);
        } else {
            print '<pre>';
            print 'You do not have permission to access that discount.';
            die();
        }
    }

    public function actionDeleteBuyXGetY($id = 0, $xy = NULL)
    {
        PromotionBuyxgety::deleteAll(["id" => $xy, "promotion_id" => $id]);
        $this->redirect(["promotions/"]);
    }

    public function actionBuyXGetY($id = 0, $xy = NULL)
    {
        $promotion = PromotionPromotion::findOne($id);
        if ($promotion && $promotion->store_id == CurrentStore::getStoreId()) {
            $new = false;
            if (isset($xy)) {
                $model = PromotionBuyxgety::findOne(["id" => $xy]);
            } else {
                $model = new PromotionBuyxgety();
                $new = true;
            }
            if ($post = Yii::$app->request->post()) {
                if ($model) {
                    $model->load($post);
                    if ($new) {
                        $model->promotion_id = $id;
                        $model->created_at = time();
                    } else {
                        $model->modified_at = time();
                    }
                    if ($model->save()) {
                        $this->redirect(["promotions/"]);
                    }
                }
            }
            return $this->render('buyxgety/index', [
                'model' => isset($model) ? $model : false,
                'id' => $id,
            ]);
        } else {
            $this->redirect(["promotions/"]);
        }
    }

    public function actionEnableCode($cid, $action = false)
    {
        if ($cid && $action) {
            $storePromo = PromotionStoreCode::findOne([
                'code_id' => $cid, 'store_id' => CurrentStore::getStoreId()
            ]);
            switch ($action) {
                case 'enable':
                    if (empty($storePromo)) {
                        $storePromo = new PromotionStoreCode();
                        $storePromo->store_id = CurrentStore::getStoreId();
                        $storePromo->code_id = $cid;
                        $storePromo->created_at = time();
                        $storePromo->save();
                    }
                    break;
                case 'disable':
                    if (!empty($storePromo)) {
                        $storePromo->delete();
                    }
                    break;
            }
        }

        return $this->redirect(['promotions/codes']);
    }

    /**
     * Creates a new PromotionCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCode()
    {
        $model = new PromotionCode();

        if ($model->load(Yii::$app->request->post())) {
            $model->code = strtoupper($model->code);
            $model->created_at = time();
            $model->save();

            if ($model->type === "Free Product(s)") {
                $model->refresh();
                return $this->redirect(["promotions/update-code?id=$model->id"]);
            }

            return $this->redirect(['promotions/codes']);
        } else {
            return $this->render('code/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PromotionCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateCode($id)
    {
        $model = $this->findCodeModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->code = strtoupper($model->code);
            $model->modified_at = time();
            $model->save();

            return $this->redirect(['promotions/codes']);
        } else {
            return $this->render('code/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PromotionCode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteCode($id)
    {
        $this->findCodeModel($id)->delete();

        return $this->redirect(['promotions/codes']);
    }

    public function actionEnablePromo($pid, $action = false)
    {
        if ($pid && $action) {
            $promotion = PromotionPromotion::findOne($pid);

            if ($promotion) {
                if ($promotion->store_id == CurrentStore::getStoreId()) {
                    $storePromotion = PromotionStorePromotion::findOne([
                        'store_id' => CurrentStore::getStoreId(),
                        'promotion_id' => $promotion->id
                    ]);

                    switch ($action) {
                        case 'enable':
                            if (empty($storePromotion)) {
                                $storePromotion = new PromotionStorePromotion();
                                $storePromotion->store_id = CurrentStore::getStoreId();
                                $storePromotion->promotion_id = $promotion->id;
                                $storePromotion->created_at = time();
                                $storePromotion->save();
                            }
                            break;

                        case 'disable':
                            if (!empty($storePromotion)) {
                                $storePromotion->delete();
                            }
                            break;
                    }
                } else {
                    // If the current store does not own the promotion,
                    // clone it under that store's ID; this allows the
                    // store to customize the parameters of the promo
                    $storePromotion = PromotionStorePromotion::find()
                        ->where(['store_id' => CurrentStore::getStoreId()])
                        ->andWhere(['IN', 'promotion_id', PromotionPromotion::find()
                            ->select('id')
                            ->where([
                                'store_id' => CurrentStore::getStoreId()
                            ])
                        ])->one();

                    switch ($action) {
                        case 'enable':
                            if (empty($storePromotion)) {
                                $time = time();
                                $clonedPromotion = PromotionPromotion::findOne([
                                    'store_id' => CurrentStore::getStoreId()
                                ]);

                                if ($clonedPromotion) {
                                    // Okay, just need to add a promotion_store_promotion row
                                    $storePromotion = new PromotionStorePromotion();
                                    $storePromotion->store_id = CurrentStore::getStoreId();
                                    $storePromotion->promotion_id = $clonedPromotion->id;
                                    $storePromotion->created_at = $time;
                                    $storePromotion->save();
                                } else {
                                    // Yikes, need to clone the entire thing...
                                    $clonedPromotion = new PromotionPromotion();
                                    $clonedPromotion->store_id = CurrentStore::getStoreId();
                                    $clonedPromotion->label = "$promotion->label Copy";
                                    $clonedPromotion->starts_at = $promotion->starts_at;
                                    $clonedPromotion->ends_at = $promotion->ends_at;
                                    $clonedPromotion->created_at = $time;

                                    if ($clonedPromotion->save()) {
                                        $parentDiscount = PromotionDiscount::findOne([
                                            'promotion_id' => $promotion->id
                                        ]);

                                        if ($parentDiscount) {
                                            $clonedDiscount = new PromotionDiscount();
                                            $clonedDiscount->store_id = CurrentStore::getStoreId();
                                            $clonedDiscount->promotion_id = $clonedPromotion->id;
                                            $clonedDiscount->label = $parentDiscount->label;
                                            $clonedDiscount->amount = $parentDiscount->discount;
                                            $clonedDiscount->type = $parentDiscount->type;
                                            $clonedDiscount->created_at = $time;

                                            if ($clonedDiscount->save()) {
                                                $parentConditions = PromotionDiscountCondition::findAll([
                                                    'discount_id' => $parentDiscount->id
                                                ]);

                                                if ($parentConditions) {
                                                    foreach ($parentConditions as $parentCondition) {
                                                        $clonedCondition = new PromotionDiscountCondition();
                                                        $clonedCondition->discount_id = $clonedDiscount->id;
                                                        $clonedCondition->condition = $parentCondition->condition;
                                                        $clonedCondition->key = $parentCondition->key;
                                                        $clonedCondition->operation = $parentCondition->operation;
                                                        $clonedCondition->value = $parentCondition->value;
                                                        $clonedCondition->created_at = $time;

                                                        if (!$clonedCondition->save())
                                                            throw new HttpException(400, 'Unable to clone discount condition.');
                                                    }

                                                    // Lastly, "enable" the promotion for the current store
                                                    $storePromotion = new PromotionStorePromotion();
                                                    $storePromotion->store_id = CurrentStore::getStoreId();
                                                    $storePromotion->promotion_id = $clonedPromotion->id;
                                                    $storePromotion->created_at = $time;

                                                    if (!$storePromotion->save())
                                                        throw new HttpException(400, 'Unable to enable that promotion.');
                                                }
                                            } else {
                                                throw new HttpException(400, 'Unable to clone discount.');
                                            }
                                        }
                                    } else {
                                        throw new HttpException(400, 'Unable to clone promotion.');
                                    }
                                }
                            }
                            break;

                        case 'disable':
                            if (!empty($storePromotion)) {
                                $storePromotion->delete();
                            }
                            break;
                    }
                }
            }
        }
        return $this->redirect(['promotions/']);
    }

    public function actionCreate()
    {
        $model = new PromotionPromotion();

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post())) {
                $model->store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : Store::NO_STORE;
                $model->starts_at = strtotime(Yii::$app->request->post('starts_at'));
                $model->ends_at = strtotime(Yii::$app->request->post('ends_at'));
                $model->created_at = time();

                if ($model->save()) {
                    return $this->redirect([
                        'add-promo',
                        'id' => $model->id
                    ]);
                } else {
                    error_log(print_r($model->getErrors(), true), 0);
                    throw new HttpException(400, 'Unable to create promotion.');
                }
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        if (isset($id)) {
            $store_id = CurrentStore::getStoreId() ? CurrentStore::getStoreId() : Store::NO_STORE;
            if ($model = PromotionPromotion::findOne(["id" => $id, "store_id" => $store_id])) {
                if (Yii::$app->request->post()) {
                    if ($model->load(Yii::$app->request->post())) {
                        $model->store_id = $store_id;
                        $model->starts_at = strtotime(Yii::$app->request->post('starts_at'));
                        $model->ends_at = strtotime(Yii::$app->request->post('ends_at'));
                        $model->created_at = time();

                        if ($model->save()) {
                            return $this->redirect([
                                'index'
                            ]);
                        } else {
                            error_log(print_r($model->getErrors(), true), 0);
                            throw new HttpException(400, 'Unable to update promotion.');
                        }
                    }
                }

                return $this->render('update', [
                    'model' => $model
                ]);
            } else {
                return $this->redirect(['promotions/']);
            }
        }
    }

    public function actionAddPromo($id = 0)
    {
        if ($id) {
            if (Yii::$app->request->post()) {
                $type = Yii::$app->request->post('type');

                switch ($type) {
                    case 'image':
                        print 'Promotional image override.';
                        break;
                    case 'discount':
                        $this->redirect(['promotions/discounts', 'id' => $id]);
                        break;
                    case 'buy_x_get_y':
                        $this->redirect(['promotions/buy-x-get-y', 'id' => $id]);
                        break;
                    case 'flyer':
                        print 'Flyer override.';
                        break;
                }
            }

            return $this->render('add', [
                'id' => $id
            ]);
        } else {
            return "Invalid or missing id";
        }
    }

    public function actionServerSideAssociatedFreeProducts()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->get();
            $columns = ["relation.id", 'name.value', 'sku.value', 'p.brand_id', 'relation.sort'];
            if (isset($request['order'][0]['column'])) {
                $order_column = $columns[$request['order'][0]['column']];
                $order_direction = $request['order'][0]['dir'];
                $order = "ORDER BY $order_column $order_direction";
            } else {
                $order = "ORDER BY relation.id desc";
                $order_column = "relation.id";
            }

            $offset = "";
            if (isset($request['start'])) {
                $offset = $request['start'] . ", ";
            }
            $group = "GROUP BY p.id";

            $limit = "";
            if (isset($request['length'])) {
                $limit .= " LIMIT $offset " . $request['length'];
            }

            $current_store_id = CurrentStore::getStoreId();
            if ($current_store_id == null) {
                $current_store_id = 0;
            }

            $connection = Yii::$app->getDb();
            $select = "SELECT p.*";
            $froms = " FROM catalog_product p ";
            $join1 = "";
            $joins = "";
            $wheres = "";
            $setFilter = false;
            if ($current_store_id == 0) {
                $countWhere1 = $where1 = "WHERE p.store_id = 0 and p.type != 'grouped'";
            } else {
                $join1 .= "LEFT JOIN `catalog_attribute_value` AS cav ON cav.`product_id` = p.`id`";
                $countWhere1 = $where1 = "WHERE (p.`id` IN (
                            SELECT `product_id`
                            FROM   `catalog_store_product`
                            WHERE  `store_id` = '$current_store_id'))  and p.type != 'grouped'";
            }

            $column = 0;

            if ($request['columns'][$column]['search']['value'] == "") {
                $request['columns'][$column]['search']['value'] = "0";
            }

            if (strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == "relation.id") {
                $joins .= "LEFT JOIN promotion_free_product as relation ON p.id = relation.product_id AND relation.promotion_id = '" . $request['pid'] . "'";
                if ($request['columns'][$column]['search']['value'] !== '0') {
                    $yesNo = ($request['columns'][$column]['search']['value'] === '1') ? "IS NOT NULL" : "IS NULL";
                    $wheres .= " AND relation.id $yesNo";
                }
            }
            $column++;
            if ($nameLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == "name.value") {
                $joins .= "LEFT JOIN catalog_attribute_value as name ON p.id = name.product_id AND name.attribute_id = 1 AND (name.store_id = $current_store_id OR name.store_id = 0)";
                if ($nameLength) {
                    $wheres .= " AND name.value LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
                }
            }
            $column++;
            if ($skuLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == "sku.value") {
                $joins .= "LEFT JOIN catalog_attribute_value as sku ON p.id = sku.product_id AND sku.attribute_id = 4 AND (sku.store_id = $current_store_id OR  sku.store_id = 0)";
                if ($skuLength) {
                    $wheres .= " AND sku.value LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
                }
            }
            $column++;
            if ($request['columns'][$column]['search']['value'] > 0) {
                $wheres .= " AND p.brand_id = '" . $request['columns'][$column]['search']['value'] . "' ";
            }

            //print_r("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");die;
            $command = $connection->createCommand("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");
            $products = $command->queryAll();
            $data = [];
            $connection = Yii::$app->getDb();

            $attributes = CatalogAttribute::find()
                ->where(['in', 'slug', ["price", "special-price", "sku", "name", "active"]])
                ->asArray()
                ->all();

            $freeProducts = ArrayHelper::getColumn(PromotionFreeProduct::find()->where(["promotion_id" => $request['pid']])->asArray()->all(), "product_id");

            foreach ($products as $product) {
                if ($request['pid'] == $product['id']) {
                    continue;
                }
                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
                $row = [];
                $checked = (in_array($product['id'], $freeProducts)) ? "checked" : "";
                $row[] = "<input type='checkbox' class='kv-row-checkbox' name='selection[]' value='3' data-id1='" . $request['pid'] . "' data-id2='" . $product['id'] . "' $checked>";
                $row[] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $row[] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $row[] = CatalogProduct::findBrandByProduct($product['brand_id']);
                $product_sort = ($product_sort = PromotionFreeProduct::findOne(["promotion_id" => $request['pid'], "product_id" => $product['id']])) ? $product_sort->sort : '';
                $row[] = ($checked) ? "<div class='input-group'><input class='form-control sort-edit sort' data-id1='" . $request['pid'] . "' data-id2='" . $product['id'] . "' type='text' value ='" . $product_sort . "'/></div>" : "";
                $data[] = $row;
            }

            $countSelect = "SELECT p.*";

            $total = count($connection->createCommand("$countSelect $froms $join1 $countWhere1 $group")->queryAll());
            if ($setFilter) {
                $filterCount = "";
            } else {
                $filterCount = count($connection->createCommand("$countSelect $froms $join1 $joins $where1 $wheres $group")->queryAll());
            }

            return json_encode([
                'draw' => $request['draw'],
                'recordsTotal' => $total,
                'recordsFiltered' => $filterCount,
                'data' => $data,
            ]);
        }
    }

    public function actionFreeProductPromotionRelationAjax()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post["isChecked"]) && !empty($post["isChecked"]) && isset($post["id1"]) && !empty($post["id1"]) && isset($post["id2"]) && !empty($post["id2"])) {
                $post["isChecked"] = ($post["isChecked"] === "true") ? true : false;
                if ($post["isChecked"]) {
                    $relation = PromotionFreeProduct::find()->Where(["promotion_id" => $post["id1"]])->andWhere(["product_id" => $post["id2"]])->one();
                    if (!$relation) {
                        $relation = new PromotionFreeProduct();
                    }
                    $relation->promotion_id = $post["id1"];
                    $relation->product_id = $post["id2"];
                    if ($relation->save()) {
                        return "<div class='input-group'><input class='form-control sort-edit sort' data-id1='" . $post['id1'] . "' data-id2='" . $post['id2'] . "' type='number' value=''></div>";
                    }
                } else {
                    $relation = PromotionFreeProduct::find()->Where(["promotion_id" => $post["id1"]])->andWhere(["product_id" => $post["id2"]])->one();
                    if ($relation) {
                        $relation->delete();
                    }
                    return "";
                }
            }

            if (isset($post["switch_key"])) {
                if ($post["switch_key"] == "sort") {
                    if ($categoryProduct = PromotionFreeProduct::findOne(["promotion_id" => $post['id1'], "product_id" => $post['id2']])) {
                        $categoryProduct->sort = $post["switch_value"];
                        if ($categoryProduct->save()) {
                            return "<div class='input-group'><input class='form-control sort-edit sort' data-id1='" . $post['id1'] . "' data-id2='" . $post['id2'] . "' type='number' value='" . $post["switch_value"] . "'></div>";
                        }
                    }
                }
            }

            return Json::encode("free product association failed");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the PromotionCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromotionCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCodeModel($id)
    {
        if (($model = PromotionCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}