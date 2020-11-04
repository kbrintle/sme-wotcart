<?php

namespace frontend\controllers;

use Yii;
use common\components\CurrentStore;
use common\models\catalog\CatalogProduct;
use yii\web\Controller;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogAttribute;

class BrandsController extends Controller
{

    public function actionIndex($stub = null) {
        if ($stub) {
            $brand = CatalogBrand::find()->where(['slug'=>$stub])->one();
            if(!$brand){
                throw new \yii\web\NotFoundHttpException();
            }
            $this->view->title = $brand->name;
            return $this->render('detail', ['brand' => $brand]);
        } else {
            $brands = CurrentStore::getStore()->storeBrands;

            if ($brands) {
                foreach ($brands as $key => $brand) {
                    $listed = CatalogProduct::find()
                        ->where([
                            'parent_id' => null
                        ])
                        ->andWhere(['IN', 'brand_id', CatalogBrandStore::find()
                            ->select('brand_id')
                            ->where([
                                'store_id' => CurrentStore::getStoreId(),
                                'brand_id' => $brand->id
                            ])
                        ])
                        ->andWhere(['NOT IN', 'id', CatalogCategoryProduct::find()
                            ->select('product_id')
                            ->where(['category_id' => CatalogCategory::find()
                                ->select('id')
                                ->where(['slug' => 'boxspring'])
                            ])
                        ])
                        ->andWhere(['IN', 'id', CatalogAttributeValue::find()
                            ->select('product_id')
                            ->where(['store_id' => CurrentStore::getStoreId()])
                            ->andWhere([
                                'attribute_id' => CatalogAttribute::findOne(['slug' => 'active'])->id,
                                'value'        => true
                            ])
                        ])
                        ->limit(1)
                        ->all();

                    if (!$listed)
                        unset($brands[$key]);
                }
            }

            $this->view->title = "Brands";
            return $this->render('index', ['brands' => $brands]);
        }

    }

}