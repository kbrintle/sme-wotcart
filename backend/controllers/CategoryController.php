<?php

namespace backend\controllers;

use backend\models\CategoryForm;
use common\models\catalog\CatalogCategoryProduct;
use Yii;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogBrand;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\CurrentStore;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\FileHelper;
use common\models\catalog\CatalogProduct;

/**
 * CategoryController implements the CRUD actions for CatalogCategory model.
 */
class CategoryController extends Controller
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
     * Lists all CatalogCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new CategoryForm();
        if (CurrentStore::isNone()) {
            $categories = CatalogCategory::find()->where(['store_id' => 0, 'is_deleted' => false])/*'is_active' => true,*/
            // ->orderBy('order')
            ->all();
        } else {
            $categories = CatalogCategory::find()
                ->where('store_id = 0')
                ->orWhere('store_id = :store_id', [':store_id' => CurrentStore::getStoreId()])
                //->andWhere('is_active = :is_active', [':is_active' => true])
                ->andWhere('is_deleted = :is_deleted', [':is_deleted' => false])
                ->all();
        }

        $data = [];
        $expanded = false;
        foreach ($categories as $category) {

            if ($category->is_active == false) {
                $category->name .= " (not active)";
            }

            if ($category->parent_id) {
                $data[$category->parent_id]['children'][] = ['title' => "$category->name", 'key' => "$category->id", 'folder' => true, 'children' => [], 'expanded' => $expanded, 'order' => "$category->order"];
            } else {
                if (array_key_exists($category->id, $data)) {
                    $data[$category->id]['title'] = $category->name;
                    $data[$category->id]['key'] = $category->id;
                    $data[$category->id]['folder'] = true;
                    $data[$category->id]['expanded'] = $expanded;
                    $data[$category->id]['order'] = $category->order;
                } else {
                    $data[$category->id] = ['title' => "$category->name", 'key' => "$category->id", 'folder' => true, 'children' => [], 'expanded' => $expanded, 'order' => "$category->order"];
                }
            }
        }

        usort($data, [$this, 'sort_objects_by_order']);
        $sortedData = [];
        foreach ($data as $d) {
            if (isset($d["children"])) {
                usort($d["children"], [$this, 'sort_objects_by_order']);
            }
            $sortedData[] = $d;
        }

        return $this->render('index', [
            'categories' => $sortedData,
            'model' => $model,
        ]);
    }

    private static function sort_objects_by_order($a, $b)
    {
        if (!isset($a['order']) || !isset($b['order'])) {
            return 0;
        }

        if ($a['order'] == $b["order"]) {
            return 0;
        }
        return ($a['order'] < $b["order"]) ? -1 : 1;
    }

    /**
     * Displays a single CatalogCategory model.
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
     * Creates a new CatalogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoryForm();

        if ($model->load(Yii::$app->request->post())) {     //load form model
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->thumbnail = UploadedFile::getInstance($model, 'thumbnail');
            $model->banner_image = UploadedFile::getInstance($model, 'banner_image');
            if ($model->validate()) {
                if ($model->upload()) {
                    //validate form model
                    if ($model->save(true)) {                   //save form model
                        return $this->redirect('index');
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatalogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $catalogBrands = ArrayHelper::map(CatalogBrand::find()->where(['is_active' => true, 'is_deleted' => false])->all(), 'id', 'name');

        $model = $this->findModel($id);

        if ($post = Yii::$app->request->post()) {

            if (isset($post["CatalogCategory"]["delete"])) {
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            }

            $oldImage = $model->image;
            $oldThumbname = $model->thumbnail;
            $oldBannerImage = $model->banner_image;
            if ($model->load($post)) {

                if ($model->validate()) {

                    $directory = Yii::getAlias('@frontend/web/uploads/category') . DIRECTORY_SEPARATOR;
                    if (!is_dir($directory)) {
                        FileHelper::createDirectory($directory);
                    }

                    $imageFile = UploadedFile::getInstance($model, 'image');
                    if ($imageFile) {
                        $fileName = str_replace(' ', '', $imageFile->name);
                        $filePath = $directory . $fileName;
                        $frontEndPath = "/category/$fileName";
                        if ($imageFile->saveAs($filePath, true)) {
                            $model->image = $frontEndPath;
                        }
                    } else {
                        $model->image = $frontEndPath = $oldImage;
                    }

                    $imageFile = UploadedFile::getInstance($model, 'thumbnail');
                    if ($imageFile) {
                        $fileName = str_replace(' ', '', $imageFile->name);
                        $filePath = $directory . $fileName;
                        $frontEndPath = "/category/$fileName";
                        if ($imageFile->saveAs($filePath, true)) {
                            $model->thumbnail = $frontEndPath;
                        }
                    } else {
                        $model->thumbnail = $frontEndPath = $oldThumbname;
                    }


                    $imageFile = UploadedFile::getInstance($model, 'banner_image');
                    if ($imageFile) {
                        $fileName = str_replace(' ', '', $imageFile->name);
                        $filePath = $directory . $fileName;
                        $frontEndPath = "/category/$fileName";
                        if ($imageFile->saveAs($filePath, true)) {
                            $model->banner_image = $frontEndPath;
                        }
                    } else {
                        $model->banner_image = $frontEndPath = $oldBannerImage;
                    }


                    /* if (isset($oldImage)) { //TODO CPM need to protect deletion of old image files like banners
                         $checkIfBeingUsed = count(StoreBanner::getBannerByImage($oldImage, CurrentStore::getStoreId()));
                         if (!$checkIfBeingUsed > 1) {
                             $file = Yii::getAlias("@frontend/web$oldImage");
                             if (is_file($file)) {
                                 unlink($file);
                             }
                         }
                     }*/

                    if ($model->save(false)) {                   //save form model
                        $model->id = $id;
                        return $this->renderPartial('update_panel', [
                            'catalogBrands' => $catalogBrands,
                            'model' => $model,
                        ]);
                    }
                } else {
                    var_dump($model->errors);
                }
            }
        } else {
            $model->id = $id;
            return $this->renderPartial('update_panel', [
                'catalogBrands' => $catalogBrands,
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing CatalogCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public
    function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = CatalogCategory::find()->where(["id" => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionAjax()
    {
        if ($moveFolder = Yii::$app->request->post('moveFolderParent')) {
            Yii::$app->db->createCommand("UPDATE `catalog_category` SET `parent_id` = NULL WHERE  `parent_id` = " . $moveFolder['id'] . " AND `id` NOT IN(" . implode(", ", $moveFolder['children']) . ")")->execute();
            foreach ($moveFolder['children'] as $key => $id) {
                $id = str_replace("_", "", $id);
                Yii::$app->db->createCommand("UPDATE `catalog_category` SET `parent_id` = " . $moveFolder['id'] . ", `order` = $key WHERE  `id` = $id")->execute();
            }
        }
        if ($moveFolder = Yii::$app->request->post('moveFolderChild')) {
            if ($moveFolder['id'] == "root_1") {
                $moveFolder['id'] = 'NULL';
                Yii::$app->db->createCommand("UPDATE `catalog_category` SET `parent_id` = NULL WHERE  `parent_id` = " . $moveFolder['id'])->execute();
            }
            foreach ($moveFolder['children'] as $key => $id) {
                $id = str_replace("_", "", $id);
                Yii::$app->db->createCommand("UPDATE `catalog_category` SET `parent_id` = " . $moveFolder['id'] . ", `order` = $key WHERE  `id` = $id")->execute();
            }
        }
        return Json::encode("success");
    }

    public function actionServerSideRelationship()
    {
        if (Yii::$app->request->isAjax) {
            // Initialize
            $request = Yii::$app->request->get();

            $columns = [
                "relation.id",
                'name.value',
                'sku.value',
                'p.brand_id',
                "relation.sort",
            ];
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
            //$group = "";
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
                $countWhere1 = $where1 = "WHERE p.store_id = 0";
            } else {
                $join1 .= "LEFT JOIN `catalog_attribute_value` AS cav ON cav.`product_id` = p.`id`";
                $countWhere1 = $where1 = "WHERE (p.`id` IN (
                            SELECT `product_id`
                            FROM   `catalog_store_product`
                            WHERE  `store_id` = '$current_store_id'))";
            }

            $colNum = 0;

            if ($request['columns'][$colNum]['search']['value'] == "") {
                $request['columns'][$colNum]['search']['value'] = "1";
            }

            if (strlen($request['columns'][$colNum]['search']['value']) > 0 || $order_column == "relation.id" || $order_column == "relation.sort") {
                $joins .= "LEFT JOIN catalog_category_product as relation ON p.id = relation.product_id AND relation.category_id = '" . $request['pid'] . "'";
                if ($request['columns'][$colNum]['search']['value'] !== '0') {
                    $yesNo = ($request['columns'][$colNum]['search']['value'] === '1') ? "IS NOT NULL" : "IS NULL";
                    $wheres .= " AND relation.id $yesNo";
                }
            }
            $colNum++;
            if ($nameLength = strlen($request['columns'][$colNum]['search']['value']) > 0 || $order_column == "name.value") {
                $joins .= "LEFT JOIN catalog_attribute_value as name ON p.id = name.product_id AND name.attribute_id = 1 AND (name.store_id = $current_store_id OR name.store_id = 0)";
                if ($nameLength) {
                    $wheres .= " AND name.value LIKE '%" . $request['columns'][$colNum]['search']['value'] . "%'";
                }
            }
            $colNum++;
            if ($skuLength = strlen($request['columns'][$colNum]['search']['value']) > 0 || $order_column == "sku.value") {
                $joins .= "LEFT JOIN catalog_attribute_value as sku ON p.id = sku.product_id AND sku.attribute_id = 4 AND (sku.store_id = $current_store_id OR  sku.store_id = 0)";
                if ($skuLength) {
                    $wheres .= " AND sku.value LIKE '%" . $request['columns'][$colNum]['search']['value'] . "%'";
                }
            }
            $colNum++;
            if ($request['columns'][$colNum]['search']['value'] > 0) {
                $wheres .= " AND p.brand_id = '" . $request['columns'][$colNum]['search']['value'] . "' ";
            }
            $colNum++;
            if ($request['columns'][$colNum]['search']['value'] > 0) {
                $wheres .= " AND relation.sort = '" . $request['columns'][$colNum]['search']['value'] . "' ";
            }


            //print_r("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");die;
            $command = $connection->createCommand("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");
            $products = $command->queryAll();
            $data = [];
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand('SELECT * FROM `catalog_attribute` WHERE `slug` IN ("price", "special-price", "sku", "name", "active");');
            $attributes = $command->queryAll();

            $relatedProducts = CatalogCategoryProduct::find()->where(["category_id" => $request['pid']])->asArray()->all();
            $relatedProductsArray = ArrayHelper::getColumn($relatedProducts, "product_id");

            foreach ($products as $product) {
                if ($request['pid'] == $product['id']) {
                    continue;
                }
                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
                $row = [];
                $checked = (in_array($product['id'], $relatedProductsArray)) ? "checked" : "";
                $row[] = "<input type='checkbox' class='kv-row-checkbox' name='selection[]' value='3' href='/admin/product/product-relation-ajax' pid='" . $product['id'] . "' cid='" . $request['pid'] . "' relationtype='' $checked>";
                $row[] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $row[] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $row[] = CatalogProduct::findBrandByProduct($product['brand_id']);
                $row[] = ($checked) ? "<div class='input-group'><input class='form-control sort-edit sort' pid='" . $product['id'] . "' cid='" . $request['pid'] . "' type='text' value ='" . $this->searchForAttribute($product['id'], "sort", $relatedProducts) . "'/></div>" : "";
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

    private static function searchForAttribute($id, $attribute, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['product_id'] === $id) {
                return $val[$attribute];
            }
        }
        return null;
    }

    public function actionUpdateAttribute()
    {
        if ($post = Yii::$app->request->post()) {
            if ($post["switch_key"] == "sort") {
                if ($categoryProduct = CatalogCategoryProduct::findOne(["category_id" => $post['cid'], "product_id" => $post['pid']])) {
                    $categoryProduct->sort = $post["switch_value"];
                    if ($categoryProduct->save()) {
                    }
                }
            }
            if ($post["switch_key"] == "unchecked") {
                if ($categoryProduct = CatalogCategoryProduct::findOne(["category_id" => $post['cid'], "product_id" => $post['pid']])) {
                    $categoryProduct->delete();
                }
                return "";
            }
            if ($post["switch_key"] == "checked") {
                if (!$categoryProduct = CatalogCategoryProduct::findOne(["category_id" => $post['cid'], "product_id" => $post['pid']])) {
                    $categoryProduct = new CatalogCategoryProduct();
                    $categoryProduct->category_id = $post['cid'];
                    $categoryProduct->product_id = $post['pid'];
                    $categoryProduct->created_at = time();
                    $categoryProduct->save();
                }
                return "<div class=\"input-group\"><input class=\"form-control sort-edit sort\" pid=\"".$post['pid']."\" cid=\"".$post['cid']."\" type=\"number\" value=\"\"></div>";
            }
            return true;
        }
    }
}