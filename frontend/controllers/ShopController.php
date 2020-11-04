<?php

namespace frontend\controllers;

use common\components\helpers\PermissionHelper;
use frontend\models\CreateReviewForm;
use Yii;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;
use frontend\models\StoreFilters;
use common\components\CurrentStore;
use common\models\core\Store;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogCategory;
use yii\web\NotFoundHttpException;
use common\models\catalog\CatalogCategoryProduct;

class ShopController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'review-submission' => ['POST']
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $this->view->title = "Shop";

        if (Yii::$app->request->isAjax) {
            $model = new StoreFilters();

            $cache = Yii::$app->cache;
            $key = 'products_' . CurrentStore::getStoreId();
            $products = $cache->get($key);
            //VarDumper::dump($products); die;
            if ($products === false) {
                $products = $model->getProducts();
                $cache->set($key, $products);
            }

            return Json::encode($products);
        }

        return $this->render('//products/index');
    }

    public function actionCategory($category)
    {
        $this->view->title = "Shop by Category";

        if (Yii::$app->request->isAjax) {
            $model = new StoreFilters();

            if (YII_ENV_DEV) {
                $products = $model->getProducts(['category' => $category]);
            } else {
                $cache = Yii::$app->cache;
                $key = 'category_products_' . $category . '_' . CurrentStore::getStoreId();
                $products = $cache->get($key);
                if ($products === false) {
                    $products = $model->getProducts(['category' => $category]);
                    $cache->set($key, $products);
                }
            }

            return Json::encode($products);
        }

        return $this->render('//products/index', [
            'category' => $category,
            'hidden_filters' => ['category'],
        ]);
    }

    public function actionBrand($brand)
    {
        $this->view->title = "Shop by Brand";

        if (Yii::$app->request->isAjax) {
            $model = new StoreFilters();
            $cache = Yii::$app->cache;
            $key = 'brand_products_' . CurrentStore::getStoreId();
            $products = $cache->get($key);
            if ($products === false) {
                $products = $model->getProducts(['brand' => $brand]);
                $cache->set($key, $products);
            }

            return Json::encode($products);
        }

        return $this->render('//products/index', [
            'hidden_filters' => ['brand']
        ]);
    }


    public function actionReviewSubmission()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $review = new CreateReviewForm();
            if ($review->load($request->post())) {
                if ($review->save()) {
                    return true;
                }
            }
        }

        throw new UserException('Something went wrong while saving your review');
    }


    public function actionView($slug)
    {

        //process review submission
        $review_form = new CreateReviewForm();
        if ($review_form->load(Yii::$app->request->post())) {
            if ($review_form->save()) {
                Yii::$app->getSession()->setFlash('success', 'Your Review was successfully saved');
            } else {
                Yii::$app->getSession()->setFlash('error', 'There was an error while saving your Review');
            }
        }

        // Confirm that this store has 'permission' to view this product
        $hasProduct = CatalogProduct::find()->storeHasProduct($slug)->all();


        if (!$hasProduct)
            PermissionHelper::notFound("Sorry, I wasn't able to locate that product.");


        $catalogProduct = CatalogProduct::find()->where(['slug' => $slug])->one();
        if (empty($catalogProduct->id))
            PermissionHelper::notFound("Sorry, I wasn't able to locate that product.");


        $this->view->title = CatalogProduct::getAttributeValue($catalogProduct->id, 'name');

        if (CatalogProduct::isChild($catalogProduct->id)) {
            $selected = $catalogProduct->id;
            $id = CatalogProduct::findOne($selected)->parent_id;
        } else {
            $id = $catalogProduct->id;
        }

        $product = CatalogProduct::findOne([
            'id' => $id,
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]
        ]);


        if (!CatalogProduct::getAttributeValue($product->id, 'active')) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!empty($product)) {
            $children = CatalogProduct::find()
                ->where(['parent_id' => $id])
                ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                    ->select('product_id')
                    ->where(['store_id' => CurrentStore::getStoreId()])
                    ->andWhere([
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'active']),
                        'value' => true
                    ])
                ])
                ->andWhere(['NOT IN', 'id', CatalogAttributeValue::find()
                    ->select('product_id')
                    ->where(['store_id' => CurrentStore::getStoreId()])
                    ->andWhere([
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'price']),
                        'value' => '0'
                    ])
                ])->all();

        }
        $crumb = null;
        if (strpos(Yii::$app->request->referrer, 'category') !== false) {
            $categoryUrl = basename(Yii::$app->request->referrer);
            if (strlen($categoryUrl) > 0) {
                if ($catalogCategory = CatalogCategory::findOne(['slug' => $categoryUrl])) {
                    if ($categoryProduct = CatalogCategoryProduct::findOne(["category_id" => $catalogCategory->id, "product_id" => $id])) {
                        $crumb = [
                            'name' => $catalogCategory->name,
                            'url' => $catalogCategory->slug,
                        ];
                    }
                }
            }
        }

        if (!isset($crumb)) {
            if ($crumbs = CatalogProduct::getProductBreadcrumbs($id)) {
                if (count($crumbs) > 0) {
                    $crumb = $crumbs[0];
                }
            }
        }

        return $this->render('/products/view', ['product_id' => $id,
            'product' => $product,
            'selects' => isset($selects) ? $selects : null,
            'options' => isset($options) ? $options : null,
            'selected' => isset($selected) ? $selected : null,
            'boxSprings' => isset($boxSprings) ? $boxSprings : null,
            'crumb' => $crumb]);
    }
}