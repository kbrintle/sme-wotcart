<?php

namespace backend\controllers;

use common\models\catalog\CatalogProductAttachment;

use common\models\utilities\MediaTemp;
use common\models\utilities\Descriptions;
use Yii;
use frontend\models\Cache;
use backend\models\ImageManager;
use backend\models\ProductGrid;
use backend\models\UploadForm;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogStoreProduct;
use common\models\core\Store;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeSetAttribute;
use common\models\catalog\CatalogAttributeSetCategory;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogCategoryMagento;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogProductAttributeSet;
use common\models\catalog\CatalogProductFeature;
use common\models\catalog\CatalogFeature;
use common\models\catalog\CatalogProductOption;
use common\models\catalog\CatalogProductOptionValue;
use common\models\catalog\CatalogProductTierPrice;
use common\models\catalog\CatalogProductRelation;
use common\models\catalog\CatalogProductRelationType;
use common\models\catalog\CatalogProductGallery;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use common\components\CurrentStore;
use backend\models\CatalogProductMetaData;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                    'json' => ['GET'],
                    'delete' => ['POST'],
                    'update-bulk' => ['POST'],
                    'update-attribute' => ['POST'],
                    'json-delete' => ['POST']
                ],
            ],
        ];
    }


    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ($post = Yii::$app->request->post()) {
            if ($post['hasEditable']) {
                $product_id = $post['editableKey'];
                $product = CatalogProduct::findOne($product_id);

                // Widget expects a JSON response
                $response = ['output' => '', 'message' => ''];

                if ($post['editableAttribute'] == "productPrice") {
                    $price = CatalogAttributeValue::find()->where(['product_id' => $post['editableKey'],
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'price'])->id,
                        'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])->one();
                    if (!$price) {
                        $price = new CatalogAttributeValue();
                        $price->product_id = $post['editableKey'];
                        $price->attribute_id = CatalogAttribute::findOne(['slug' => 'price'])->id;
                        $price->store_id = CurrentStore::getStoreId();
                        $price->created_at = time();
                    } else {
                        $price->modified_at = time();
                    }
                    $price->value = $post["CatalogProduct"][$post['editableIndex']][$post['editableAttribute']];

                    if (!$price->save(true)) {
                        $response['message'] = $price->getErrors();
                    } else {
                        $response['output'] = number_format($price->value, 2, '.', ',');
                    }
                }

                if ($post['editableAttribute'] == "productSpecialPrice") {
                    $specialprice = CatalogAttributeValue::find()->where(['product_id' => $post['editableKey'],
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'special-price'])->id,
                        'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])->one();


                    if (!$specialprice) {
                        $specialprice = new CatalogAttributeValue();
                        $specialprice->product_id = $post['editableKey'];
                        $specialprice->attribute_id = CatalogAttribute::findOne(['slug' => 'special-price'])->id;
                        $specialprice->store_id = CurrentStore::getStoreId();
                        $specialprice->created_at = time();
                    } else {
                        $specialprice->modified_at = time();
                    }
                    $specialprice->value = $post["CatalogProduct"][$post['editableIndex']][$post['editableAttribute']];

                    if (!$specialprice->save(true)) {
                        $response['message'] = $specialprice->getErrors();
                    } else {
                        $response['output'] = number_format($specialprice->value, 2, '.', ',');
                    }
                }
                return Json::encode($response);
            }
        }

        return $this->render('index');
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @param string $tid , Product type
     * @param integer $aid , Associated attribute set
     * @param integer $pid , Parent product ID
     * @return mixed
     */
    public function actionCreate($tid = '', $aid = 0, $pid = 0)
    {
        $model = new CatalogProduct();
        $categoryProductModel = new CatalogCategoryProduct();
        $attributeValueModel = new CatalogAttributeValue();
        $uploadForm = new UploadForm();
        $catalogProductFeature = new CatalogProductFeature();

        // Features
        $features = CatalogFeature::find()//::findAll() is a non-chainable method - and we want to sort the results in query instead of in memory (faster)
        ->where([
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
            'is_active' => true,
            'is_deleted' => false
        ])
            ->orderBy('name ASC')
            ->all();
        $features = ArrayHelper::map($features, 'id', 'name');  //ArrayHelper::map returns a $k=>$v array based on the object's attributes or an empty array

        // Categories
        $categories = CatalogCategory::find()
            ->where([
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'is_active' => true,
                'is_deleted' => false
            ])
            ->orderBy('name ASC')
            ->all();
        $categoriesArray = ArrayHelper::map($categories, 'id', 'name');

        // Brands
        $brands = [];
        if (CurrentStore::isNone()) {
            $brands = CatalogBrand::find()->where([
                'store_id' => Store::NO_STORE,
                'is_active' => true,
                'is_deleted' => false,
            ])
                ->orderBy('name ASC')
                ->all();;
        } else {
            $storeBrands = CatalogBrandStore::findAll([
                'store_id' => CurrentStore::getStoreId()
            ]);
            $brandIds = ArrayHelper::getColumn($storeBrands, 'brand_id');
            $brands = CatalogBrand::find()
                ->where([
                    'id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false,
                ])
                ->orderBy('name ASC')
                ->all();
        }
        $brandsArray = ArrayHelper::map($brands, 'id', 'name');


        if (empty($aid) && !empty($pid)) {
            // If there's no incoming $aid, but this is
            // a child-simple product, use the attributes
            // associated with the parent, if any
            $aid = CatalogProductAttributeSet::find()
                ->where([
                    'product_id' => $pid,
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'is_active' => true,
                    'is_deleted' => false
                ])
                ->orderBy(['store_id' => SORT_DESC])
                ->one()->set_id;
        }

        if ($post = Yii::$app->request->post()) {

            if ($pid) {
                $productParent = CatalogProduct::findOne(['id' => $pid]);
            }
            // Create the product record
            if ($pid) {
                $model->parent_id = $pid;
                $model->brand_id = $productParent->brand_id;
            } else {
                $model->slug = $_POST['CatalogProduct']['slug'];
                $model->brand_id = $_POST['CatalogProduct']['brand_id'];

            }
            $model->store_id = CurrentStore::getStoreId();
            $model->type = $tid;
            $model->created_at = time();
            if ($model->save()) {
                $model->refresh();

                $attributeSet = new CatalogProductAttributeSet();
                $attributeSet->set_id = $aid;
                $attributeSet->product_id = $model->id;
                $attributeSet->created_at = time();
                $attributeSet->store_id = CurrentStore::getStoreId();
                $attributeSet->is_active = 1;
                $attributeSet->is_deleted = 0;
                $attributeSet->save();


                if ($categories = Yii::$app->request->post('CatalogCategoryProduct')) {
                    CatalogCategoryProduct::deleteAll(['product_id' => $model->id]);
                    foreach ($categories['category_id'] as $k => $category) {
                        $categoryProductModel = new CatalogCategoryProduct();
                        $categoryProductModel->product_id = $model->id;
                        $categoryProductModel->category_id = $category;
                        $categoryProductModel->created_at = time();
                        $categoryProductModel->save();
                    }
                }

                //CatalogProductFeature Save
                if (isset($post['CatalogProductFeature']['feature_id'])) {
                    $feature_ids = $post['CatalogProductFeature']['feature_id'];
                    foreach ($feature_ids as $feature_id) {
                        $productFeature = new CatalogProductFeature();
                        $productFeature->product_id = $model->id;
                        $productFeature->feature_id = $feature_id;
                        $productFeature->save(false);
                    }
                }

                $id = $model->id;
                if (isset($_POST['AttributeForm']['options']) && !empty($_POST['AttributeForm']['options'])) {
                    $options = $_POST['AttributeForm']['options'];
                    $model->has_options = true;
                    $model->save();
                    $productOptionsSaved = [];
                    $order = 1;
                    foreach ($options as $option) {
                        $productOption = false;
                        if (array_key_exists('option_id', $option)) { //existing option update
                            $productOption = CatalogProductOption::findOne(['product_id' => $id, 'option_id' => $option['option_id']]);
                        }
                        if (!$productOption) {
                            $productOption = new CatalogProductOption();
                        }
                        $productOption->product_id = $model->id;
                        $productOption->type = $option['type'];
                        $productOption->is_required = $option['is_required'];
                        $productOption->title = $option['title'];
                        $productOption->sort_order = $order;
                        $productOption->save();
                        $productOption->refresh();
                        $productOptionValuesSaved = [];
                        $productOptionsSaved[] = $productOption->option_id;

                        if (array_key_exists('values', $option)) {
                            $valOrder = 1;
                            foreach ($option['values'] as $value) {
                                $productOptionValue = false;
                                if (array_key_exists('option_value_id', $value)) {
                                    $productOptionValue = CatalogProductOptionValue::findOne(['option_value_id' => $value['option_value_id']]);
                                    if ($productOptionValue->store_id != CurrentStore::getStoreId()) {
                                        if ($productOptionValue->price != $value['price'] || $productOptionValue->sku != $value['sku'] || $productOptionValue->title != $value['title']) {
                                            $productOptionValuesSaved[] = $productOptionValue->option_value_id;
                                            $productOptionValue = new CatalogProductOptionValue();
                                            $productOptionValue->store_id = CurrentStore::getStoreId();
                                        }
                                    }
                                }
                                if (!$productOptionValue) {
                                    $productOptionValue = new CatalogProductOptionValue();
                                }
                                $productOptionValue->option_id = $productOption->option_id;
                                $productOptionValue->title = $value['title'];
                                $productOptionValue->price = $value['price'];
                                $productOptionValue->sku = $value['sku'];
                                $productOptionValue->sort_order = $valOrder;
                                $productOptionValue->save();
                                $productOptionValue->refresh();
                                $productOptionValuesSaved[] = $productOptionValue->option_value_id;
                                $valOrder++;
                            }
                            $order++;
                        }
                        CatalogProductOptionValue::deleteAll(['AND', ['NOT IN', 'option_value_id', $productOptionValuesSaved], ['option_id' => $productOption->option_id], ['store_id' => CurrentStore::getStoreId()]]);
                    }
                    CatalogProductOption::deleteAll(['AND', ['NOT IN', 'option_id', $productOptionsSaved], ['product_id' => $id], ['store_id' => CurrentStore::getStoreId()]]);
                } else { //delete whats there
                    $options = CatalogProductOption::find()->where(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()])->orderBy(['option_id' => SORT_DESC])->all();
                    $model->save();
                    if ($options) {
                        foreach ($options as $option) {
                            CatalogProductOptionValue::deleteAll(['option_id' => $option->option_id, 'store_id' => CurrentStore::getStoreId()]);
                        }
                        CatalogProductOption::deleteAll(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()]);
                        $options = CatalogProductOption::find()->where(['product_id' => $id])->all();
                        if (!$options) {
                            $model->has_options = false;
                        }
                    }
                }

                if (isset($_POST['AttributeForm']['tier-pricing']) && !empty($_POST['AttributeForm']['tier-pricing'])) {

                    $tiers = $_POST['AttributeForm']['tier-pricing'];
                    foreach ($tiers as $tier) {
                        $productTier = null;
                        if (array_key_exists('id', $tier)) { //existing option update
                            $productTier = CatalogProductTierPrice::findOne(['id' => $tier['id'], 'product_id' => $model->id, 'store_id' => CurrentStore::getStoreId()]);
                        }
                        if (empty($productTier)) {
                            $productTier = new CatalogProductTierPrice();
                        }
                        $productTier->product_id = $model->id;
                        $productTier->store_id = CurrentStore::getStoreId();
                        $productTier->value = $tier['value'];
                        $productTier->qty = $tier['qty'];
                        $productTier->save();

                    }
                } else { //delete whats there
                    CatalogProductTierPrice::deleteAll(['product_id' => $model->id, 'store_id' => CurrentStore::getStoreId()]);
                }


                if (array_key_exists("Attribute", Yii::$app->request->post())) {
                    foreach (Yii::$app->request->post('Attribute') as $vid => $attributeValue) {
                        // Is this a multiple select?
                        if (is_array($attributeValue)) {
                            // Remove the existing entries (update isn't possible
                            // unless we also track the previous value)
                            CatalogAttributeValue::deleteAll([
                                'attribute_id' => $vid,
                                'store_id' => CurrentStore::getStoreId(),
                                'product_id' => $model->id
                            ]);

                            foreach ($attributeValue as $singleAttributeValue) {
                                $attributeValueModel = new CatalogAttributeValue();
                                $attributeValueModel->attribute_id = $vid;
                                $attributeValueModel->store_id = CurrentStore::getStoreId();
                                $attributeValueModel->product_id = $model->id;
                                $attributeValueModel->value = $singleAttributeValue;
                                $attributeValueModel->save();
                            }
                        } else {
                            $attributeValueModel = CatalogAttributeValue::findOne([
                                'attribute_id' => $vid,
                                'store_id' => CurrentStore::getStoreId(),
                                'product_id' => $model->id
                            ]);

                            // If no value exists for this attribute, or if the
                            // incoming value is different from the current
                            if (!isset($attributeValueModel) || $attributeValue != $attributeValueModel->value) {
                                if ($attributeValueModel) {
                                    $attributeValueModel->value = $attributeValue;
                                    $attributeValueModel->update();
                                } else {
                                    $attributeValueModel = new CatalogAttributeValue();
                                    $attributeValueModel->attribute_id = $vid;
                                    $attributeValueModel->store_id = CurrentStore::getStoreId();
                                    $attributeValueModel->product_id = $model->id;
                                    $attributeValueModel->value = $attributeValue;
                                    $attributeValueModel->save();
                                }
                            }
                        }
                    }
                }
                CatalogStoreProduct::deleteAll(['product_id' => $model->id]);
                if (array_key_exists('stores', $post)) {
                    foreach (Yii::$app->request->post('stores') as $id => $value) {
                        $catalogStoreProduct = new CatalogStoreProduct();
                        $catalogStoreProduct->store_id = $id;
                        $catalogStoreProduct->product_id = $model->id;
                        $catalogStoreProduct->save(false);
                    }
                }


                /**
                 * @NOTE this is one way to do model relationships
                 *
                 *  use a non-db param in the model to hold temporary fields and then
                 *  use a singular function to unset all relationships and set the ones
                 *  that are included in the model loader. in theory, any time you update
                 *  a multiselect/relationship you select the ones you want de-select the
                 *  ones you don't want - so unlinking all and then relinking works out.
                 */


                if ($model->load($post)) {
                    $model->saveFeatures();
                }

                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        // Attribute Sets, Attributes
        $attributes = [];
        $attributeIds = [];
        // Fetch all the attributes associated with attribute set $aid
        if (!empty($aid)) {
            $attributeSetAttributes = CatalogAttributeSetAttribute::findAll(['set_id' => $aid]);
            if (!empty($attributeSetAttributes)) {
                foreach ($attributeSetAttributes as $attributeSetAttribute) {
                    $attributeIds[] = $attributeSetAttribute->attribute_id;
                }
            }
        }
        // Fetch all default attributes paired with NO STORE
        $defaultAttributes = CatalogAttribute::findAll([
            'store_id' => Store::NO_STORE, 'is_active' => true, 'is_deleted' => false, 'is_default' => true
        ]);
        if (!empty($defaultAttributes)) {
            foreach ($defaultAttributes as $defaultAttribute) {
                $attributeIds[] = $defaultAttribute->id;
            }
        }
        // Deduplicate the resulting array and expand the result set
        // into objects grouped into sub-arrays by attribute category
        $attributeIds = array_unique($attributeIds);
        $attributeSetCategories = CatalogAttributeSetCategory::findAll([
            'is_active' => true, 'is_deleted' => false
        ]);
        if (!empty($attributeSetCategories)) {
            foreach ($attributeSetCategories as $attributeSetCategory) {
                $attributes[$attributeSetCategory->label] =
                    CatalogAttribute::find()->where([
                        'id' => $attributeIds,
                        'is_active' => true,
                        'is_deleted' => false,
                        'category_id' => $attributeSetCategory->id
                    ])->orderBy(['category_id' => SORT_ASC, 'sort' => SORT_ASC])->all();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'product_type' => $tid,
            'catalogProductFeature' => $catalogProductFeature,
            'category_ids' => [],
            'categoryProductModel' => $categoryProductModel,
            'attributeValueModel' => $attributeValueModel,
            'uploadForm' => $uploadForm,
            'categoriesArray' => isset($categoriesArray) ? $categoriesArray : [],
            'brandsArray' => isset($brandsArray) ? $brandsArray : [],
            'featuresArray' => $features,  //by using ArrayHelper::map() we don't need to check if it's set - it's always set as an array with differing count
            'featuresOptions' => [],         //there are no preselected options, but we need this to be extant
            'attributes' => $attributes,
            'isCreate' => true,
            'isChildSimple' => $tid == CatalogProduct::CHILD_SIMPLE ? true : null,
            'isStandaloneSimple' => $tid == CatalogProduct::SIMPLE ? true : null,
        ]);
    }

    /**
     * When creating a new product, set that product's type.
     * @return string
     */
    public function actionNew()
    {
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('type', false)) {
                switch (Yii::$app->request->post('type')) {
                    case CatalogProduct::CHILD_SIMPLE:
                        $this->redirect(['parent', 'tid' => Yii::$app->request->post('type')]);
                        break;

                    default:
                        $this->redirect(['attributes', 'tid' => Yii::$app->request->post('type')]);
                        break;
                }
            }
        }

        // Find configurable Products
        if (CurrentStore::isNone()) {
            $configurables = CatalogProduct::findAll([
                'store_id' => Store::NO_STORE,
                'type' => CatalogProduct::CONFIGURABLE,
                'is_active' => true,
                'is_deleted' => false
            ]);

            $grouped = CatalogProduct::findAll([
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'type' => CatalogProduct::GROUPED,
                'is_active' => true,
                'is_deleted' => false
            ]);
        } else {
            $brandIds = [];
            $brands = CatalogBrandStore::findAll(['store_id' => CurrentStore::getStoreId()]);
            if (!empty($brands)) {
                foreach ($brands as $brand) {
                    $brandIds[] = $brand->brand_id;
                }
                $configurables = CatalogProduct::findAll([
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'type' => CatalogProduct::CONFIGURABLE,
                    'brand_id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false
                ]);

                $grouped = CatalogProduct::findAll([
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'type' => CatalogProduct::GROUPED,
                    'brand_id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false
                ]);


            }
        }

        // If configurable products exist, users can create child simples
        if (isset($configurables) && !empty($configurables)) {
            $productTypes = [
                CatalogProduct::CHILD_SIMPLE => ucfirst(str_replace('-', ' ', CatalogProduct::CHILD_SIMPLE)),
                CatalogProduct::SIMPLE => ucfirst(CatalogProduct::SIMPLE),
                //CatalogProduct::CONFIGURABLE => ucfirst(CatalogProduct::CONFIGURABLE),
                CatalogProduct::GROUPED => ucfirst(CatalogProduct::GROUPED)
            ];
        } elseif (isset($grouped) && !empty($grouped)) {
            $productTypes = [
                CatalogProduct::CHILD_SIMPLE => ucfirst(str_replace('-', ' ', CatalogProduct::CHILD_SIMPLE)),
                CatalogProduct::SIMPLE => ucfirst(CatalogProduct::SIMPLE),
                //CatalogProduct::CONFIGURABLE => ucfirst(CatalogProduct::CONFIGURABLE),
                CatalogProduct::GROUPED => ucfirst(CatalogProduct::GROUPED)
            ];
        } else {
            $productTypes = [
                CatalogProduct::SIMPLE => ucfirst(CatalogProduct::SIMPLE),
                // CatalogProduct::CONFIGURABLE => ucfirst(CatalogProduct::CONFIGURABLE),
                CatalogProduct::GROUPED => ucfirst(CatalogProduct::GROUPED)
            ];
        }

        return $this->render('type', [
            'productTypes' => $productTypes
        ]);
    }

    /**
     * When creating a new simple child product, associate parent.
     * @param string $tid
     * @return integer
     */
    public function actionParent($tid = '')
    {
        $parent_products = [];
        if (Yii::$app->request->post()) {
            if (Yii::$app->request->post('parent_id', false)) {
                $this->redirect([
                    'create', 'tid' => $tid, 'pid' => Yii::$app->request->post('parent_id')
                ]);
            } else {
                Yii::$app->session->setFlash('error', "No  parent configurable/grouped product Set Selected");
            }
        }

        // Fetch eligible parent configurable products
        if (CurrentStore::isNone()) {
            $configurables = CatalogProduct::findAll([
                'store_id' => Store::NO_STORE,
                'type' => CatalogProduct::CONFIGURABLE,
                'is_active' => true,
                'is_deleted' => false
            ]);
            $grouped = CatalogProduct::findAll([
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'type' => CatalogProduct::GROUPED,
                'is_active' => true,
                'is_deleted' => false
            ]);
        } else {
            $brandIds = [];
            $brands = CatalogBrandStore::findAll(['store_id' => CurrentStore::getStoreId()]);
            if (!empty($brands)) {
                foreach ($brands as $brand) {
                    $brandIds[] = $brand->brand_id;
                }
                $configurables = CatalogProduct::findAll([
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'type' => CatalogProduct::CONFIGURABLE,
                    'brand_id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false
                ]);

                $grouped = CatalogProduct::findAll([
                    'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                    'type' => CatalogProduct::GROUPED,
                    'brand_id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false
                ]);
            }
        }

        if (!empty($configurables)) {
            foreach ($configurables as $configurable) {
                $configurableValue = CatalogAttributeValue::find()
                    ->where(['attribute_id' => CatalogAttribute::findOne(['label' => 'Name'])->id])
                    ->andWhere(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                    ->andWhere(['product_id' => $configurable->id])
                    ->andWhere(['is_active' => true])
                    ->andWhere(['is_deleted' => false])
                    ->orderBy(['store_id' => SORT_DESC])
                    ->one();
                $parent_products[$configurable->id] = $configurableValue->value;
            }
        }

        if (!empty($grouped)) {
            foreach ($grouped as $group) {
                $groupedValue = CatalogAttributeValue::find()
                    ->where(['attribute_id' => CatalogAttribute::findOne(['label' => 'Name'])->id])
                    ->andWhere(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
                    ->andWhere(['product_id' => $group->id])
                    ->orderBy(['store_id' => SORT_DESC])
                    ->one();
                if ($groupedValue) {
                    $parent_products[$group->id] = $groupedValue->value;
                }

            }
        }

        return $this->render('parent', [
            'parent_products' => $parent_products
        ]);
    }

    /**
     * When creating a new product, associate additional attributes by set.
     * @param string $tid
     * @return integer
     */
    public function actionAttributes($tid = '')
    {
        $availableSets = CatalogAttributeSet::find()
            ->where(['store_id' => Store::NO_STORE])
            ->orWhere(['store_id' => CurrentStore::getStoreId()])
            ->andWhere(['is_active' => true])
            ->andWhere(['is_deleted' => false])
            ->all();
        if (!empty($availableSets) && !Yii::$app->request->post()) {
            foreach ($availableSets as $availableSet) {
                $pivotsOn = CatalogAttributeSetAttribute::findOne([
                    'set_id' => $availableSet->id,
                    'is_pivot' => true
                ]);

                if ($pivotsOn) {
                    $pivotsOn = CatalogAttribute::findOne($pivotsOn->attribute_id)->label;
                    $attributeSets[$availableSet->id] = "$availableSet->label (Links to child products on $pivotsOn)";
                } else {
                    $attributeSets[$availableSet->id] = $availableSet->label;
                }
            }
            return $this->render('attributes', [
                'attributeSets' => (isset($attributeSets) ? $attributeSets : []),
            ]);
        }

        if ((strlen($_POST['AttributeSet']['set_id']) < 1) || (!isset($_POST['AttributeSet']['set_id']))) {
            Yii::$app->session->setFlash('error', "No Attribute Set Selected");
            $this->redirect(['attributes', 'tid' => $tid]);
        } else {
            $this->redirect(['create', 'tid' => $tid, 'aid' => $_POST['AttributeSet']['set_id']]);
        }
    }

    /**
     * Add or remove a product, $pid, from a store's active catalog
     * @param $action
     */
    public function actionCarry($action = 0)
    {
        if (Yii::$app->request->post() && $action) {
            $pid = Yii::$app->request->post('pid');
            $product = CatalogStoreProduct::findOne([
                'store_id' => CurrentStore::getStoreId(),
                'product_id' => $pid
            ]);

            switch ($action) {
                case 'add':
                    if (empty($product)) {
                        $product = new CatalogStoreProduct();
                        $product->store_id = CurrentStore::getStoreId();
                        $product->product_id = $pid;
                        $product->independent = (CatalogProduct::findOne($pid)->type == CatalogProduct::CHILD_SIMPLE ? 0 : 1);
                        $product->is_visible = true;
                        $product->created_at = time();
                        $product->save();
                    }
                    break;
                case 'remove':
                    if (!empty($product)) {
                        $product->delete();
                    }
                    break;
            }
            //Clear Product Cache
            $cache = Yii::$app->cache;
            $key = 'products_' . CurrentStore::getStoreId();
            $cache->delete($key);
        }

        $this->redirect(['index']);
    }


    /**
     * Add or remove a product, $pid, from a store's active catalog
     * @param $action
     */
    public function actionAssignCategories()
    {
        $products = CatalogProduct::find()->where(['is_active' => true])->all();

        foreach ($products as $product) {
            $legacy_cat_ids = explode(',', $product->legacy_category_ids);

            foreach ($legacy_cat_ids as $id) {
                $mag_cat = CatalogCategoryMagento::find()->where(['category_id' => $id])->one();
                if (isset($mag_cat->slug) && !empty($mag_cat->slug)) {
                    $wot_cat = CatalogCategory::find()->where(['slug' => $mag_cat->slug])->one();
                }

                if ($wot_cat) {
                    $catalog_product = CatalogCategoryProduct::find()->where([
                        'product_id' => $product->id,
                        'category_id' => $wot_cat->id,
                    ])->exists();

                    if (!$catalog_product) {
                        $catalog_product = new CatalogCategoryProduct();
                        $catalog_product->product_id = $product->id;
                        $catalog_product->category_id = $wot_cat->id;
                        $catalog_product->is_active = true;
                        $catalog_product->created_at = time();
                        $catalog_product->save();

                    }
                }
            }
        }

    }

    /**
     * Add or remove a product, $pid, from a store's active catalog
     * @param $action
     */
    public function actionActive($action = 0)
    {
        if (Yii::$app->request->post() && $action) {
            $pid = Yii::$app->request->post('pid');

            switch ($action) {
                case 'add':
                    CatalogAttributeValue::setValue('active', 1, $pid);
                    break;
                case 'remove':
                    CatalogAttributeValue::setValue('active', 0, $pid);
                    break;
            }
        }

        //Clear Product Cache
        $cache = Yii::$app->cache;
        $key = 'products_' . CurrentStore::getStoreId();
        $cache->delete($key);
        Yii::$app->end();
    }

    /**
     * Add or remove a product, $pid, from a store's active catalog
     * @param $action
     */
    public function actionVisible($action = 0)
    {
        if (Yii::$app->request->post() && $action) {
            $pid = Yii::$app->request->post('pid');

            switch ($action) {
                case 'add':
                    CatalogAttributeValue::setValue('visible', 1, $pid);
                    break;
                case 'remove':
                    CatalogAttributeValue::setValue('visible', 0, $pid);
                    break;
            }
        }

        //Clear Product Cache
        $cache = Yii::$app->cache;
        $key = 'products_' . CurrentStore::getStoreId();
        $cache->delete($key);
        Yii::$app->end();
    }

    /**
     * Toggle a product's, $pid, contextual independence
     * @param $action
     */
    public function actionIndependent($action = 0)
    {
        if (Yii::$app->request->post() && $action) {
            $pid = Yii::$app->request->post('pid');

            if (CurrentStore::isNone()) {
                $product = CatalogProduct::findOne([
                    'store_id' => Store::NO_STORE,
                    'product_id' => $pid
                ]);
            } else {
                $product = CatalogStoreProduct::findOne([
                    'store_id' => CurrentStore::getStoreId(),
                    'product_id' => $pid
                ]);
            }

            if ($product) {
                switch ($action) {
                    case 'add':
                        $product->independent = true;
                        $product->save();
                        break;
                    case 'remove':
                        $product->independent = 0;
                        $product->save();
                        break;
                }
            }
            //Clear Product Cache
            $cache = Yii::$app->cache;
            $key = 'products_' . CurrentStore::getStoreId();
            $cache->delete($key);
        }

        $this->redirect(['index']);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

//        if ($model)
//            PermissionHelper::byStore($model, "Sorry, you don't have permission to update this product.");
        $category_ids = [];
        $categoryProductModel = CatalogCategoryProduct::findAll(['product_id' => $id]);
        foreach ($categoryProductModel as $cpm) {
            $category_ids[] = $cpm->category_id;
        }

        $catalogProductFeature = new CatalogProductFeature();
        ///$catalogProductFeature = CatalogProductFeature::findAll(['product_id' => $id]);

        // Features
        $features = CatalogFeature::find()//::findAll() is a non-chainable method - and we want to sort the results in query instead of in memory (faster)
        ->where([
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
            'is_active' => true,
            'is_deleted' => false
        ])
            ->orderBy('name ASC')
            ->all();
        $features = ArrayHelper::map($features, 'id', 'name');  //ArrayHelper::map returns a $k=>$v array based on the object's attributes or an empty array

        // Categories
        $categories = CatalogCategory::find()
            ->where([
                'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
                'is_active' => true,
                'is_deleted' => false
            ])
            ->orderBy('name ASC')
            ->all();
        $categoriesArray = ArrayHelper::map($categories, 'id', 'name');

        // Brands
        $brands = [];

        if (CurrentStore::isNone()) {
            $brands = CatalogBrand::find()->where([
                'store_id' => Store::NO_STORE,
                'is_active' => true,
                'is_deleted' => false
            ])
                ->orderBy('name ASC')
                ->all();
        } else {
            $storeBrands = CatalogBrandStore::findAll([
                'store_id' => CurrentStore::getStoreId()
            ]);
            $brandIds = ArrayHelper::getColumn($storeBrands, 'brand_id');
            $brands = CatalogBrand::find()
                ->where([
                    'id' => $brandIds,
                    'is_active' => true,
                    'is_deleted' => false
                ])
                ->orderBy('name ASC')
                ->all();
        }
        $brandsArray = ArrayHelper::map($brands, 'id', 'name');


        /**
         * we need to pre-select the selected options to pass into the dropDownList ActiveField
         * by getting the CatalogProduct's current CatalogFeatures and creating a map of the
         * required 'selected' attributes - which we'll send back to the ActiveField
         */
        $preselected_features = ArrayHelper::getColumn($model->features, 'id');
        $features_options = [];
        foreach ($features as $k => $v) {
            if (in_array($k, $preselected_features)) {
                //set selected here
                $features_options[$k] = array('selected' => 'selected');
            }
        }

        $uploadForm = new UploadForm();

        $aid = CatalogProductAttributeSet::find()
            ->where(['product_id' => $model->parent_id ? $model->parent_id : $id])
            ->andWhere(['store_id' => [Store::NO_STORE, CurrentStore::getStoreId()]])
            ->andWhere(['is_active' => true])
            ->andWhere(['is_deleted' => false])
            ->orderBy(['store_id' => SORT_DESC])
            ->one();

        if ($aid) {
            $aid = $aid->set_id;
        } else {
            $aid = 8;
        }

        if ($post = Yii::$app->request->post()) {
            if (array_key_exists('hasEditable', $post)) {
                if (array_key_exists('CatalogProductGallery', $post)) {
                    if ($post['hasEditable']) {
                        $galleryId = $post['editableKey'];
                        $productGallery = CatalogProductGallery::findOne($galleryId);

                        // Widget expects a JSON response
                        $response = ['output' => '', 'message' => ''];

                        $posted = current($post['CatalogProductGallery']);
                        $posted = ['CatalogProductGallery' => $posted];

                        if ($productGallery->load($posted)) {
                            $productGallery->save();
                        } else {
                            $response['message'] = $productGallery->getErrors();
                        }
                        return Json::encode($response);
                    }
                }
            }

            $model = CatalogProduct::findOne($id);

            if ($categories = Yii::$app->request->post('CatalogCategoryProduct')) {
                CatalogCategoryProduct::deleteAll(['product_id' => $id]);
                foreach ($categories['category_id'] as $k => $category) {
                    $categoryProductModel = new CatalogCategoryProduct();
                    $categoryProductModel->product_id = $id;
                    $categoryProductModel->category_id = $category;
                    $categoryProductModel->created_at = time();
                    $categoryProductModel->save(false);
                }

            }

            if (Yii::$app->request->post('CatalogProduct', false)) {
                $model->load(Yii::$app->request->post());
                $model->needs_seachanise_update = 1;
                $model->modified_at = time();
                $model->save();
            }

            //CatalogProductFeature Save
            if (isset($_POST['CatalogProductFeature']['feature_id'])) {
                $feature_ids = $_POST['CatalogProductFeature']['feature_id'];
                foreach ($feature_ids as $feature_id) {
                    $productFeature = new CatalogProductFeature();
                    $productFeature->product_id = $model->id;
                    $productFeature->feature_id = $feature_id;
                    $productFeature->save(false);
                }
            }

            if (isset($_POST['AttributeForm']['options']) && !empty($_POST['AttributeForm']['options'])) {
                $options = $_POST['AttributeForm']['options'];
                $model->has_options = true;
                $model->save();
                $productOptionsSaved = [];
                $order = 1;
                $newOption = true;
                foreach ($options as $option) {
                    $productOption = false;
                    if (array_key_exists('option_id', $option)) { //existing option update
                        $productOption = CatalogProductOption::findOne(['product_id' => $id, 'option_id' => $option['option_id']]);
                        if ($productOption->store_id != CurrentStore::getStoreId()) {
                            if ($productOption->type != $option['type'] || $productOption->title != $option['title'] || $productOption->is_required != $option['is_required']) {
                                $productOption = new CatalogProductOption();
                                $newOption = true;
                                $productOption->store_id = CurrentStore::getStoreId();
                            }
                        }
                    }
                    if (!$productOption) {
                        $productOption = new CatalogProductOption();
                        $productOption->store_id = CurrentStore::getStoreId();
                    }
                    $productOption->product_id = $model->id;
                    $productOption->type = $option['type'];
                    $productOption->is_required = $option['is_required'];
                    $productOption->title = $option['title'];
                    $productOption->sort_order = $order;
                    $productOption->save();
                    $productOption->refresh();
                    $productOptionValuesSaved = [];
                    $productOptionsSaved[] = $productOption->option_id;

                    if (array_key_exists('values', $option)) {
                        $valOrder = 1;
                        foreach ($option['values'] as $value) {
                            $productOptionValue = false;
                            if (array_key_exists('option_value_id', $value)) {
                                $productOptionValue = CatalogProductOptionValue::findOne(['option_value_id' => $value['option_value_id']]);
                                if ($productOptionValue->store_id != CurrentStore::getStoreId()) {
                                    if ($productOptionValue->price != $value['price'] || $productOptionValue->sku != $value['sku'] || $productOptionValue->title != $value['title']) {
                                        $productOptionValuesSaved[] = $productOptionValue->option_value_id;
                                        $productOptionValue = new CatalogProductOptionValue();
                                        $productOptionValue->store_id = CurrentStore::getStoreId();
                                    }
                                }
                            }
                            if (!$productOptionValue || $newOption) {
                                $productOptionValue = new CatalogProductOptionValue();
                                $productOptionValue->store_id = CurrentStore::getStoreId();
                            }
                            $productOptionValue->option_id = $productOption->option_id;
                            $productOptionValue->title = $value['title'];
                            $productOptionValue->price = $value['price'];
                            $productOptionValue->sku = $value['sku'];
                            $productOptionValue->sort_order = $valOrder;
                            $productOptionValue->save();
                            $productOptionValue->refresh();
                            $productOptionValuesSaved[] = $productOptionValue->option_value_id;
                            $valOrder++;
                        }
                        $order++;
                    }
                    CatalogProductOptionValue::deleteAll(['AND', ['NOT IN', 'option_value_id', $productOptionValuesSaved], ['option_id' => $productOption->option_id], ['store_id' => CurrentStore::getStoreId()]]);
                }
                CatalogProductOption::deleteAll(['AND', ['NOT IN', 'option_id', $productOptionsSaved], ['product_id' => $id], ['store_id' => CurrentStore::getStoreId()]]);
            } else { //delete whats there
                $options = CatalogProductOption::find()->where(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()])->orderBy(['option_id' => SORT_DESC])->all();
                $model->save();
                if ($options) {
                    foreach ($options as $option) {
                        CatalogProductOptionValue::deleteAll(['option_id' => $option->option_id, 'store_id' => CurrentStore::getStoreId()]);
                    }
                    CatalogProductOption::deleteAll(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()]);
                    $options = CatalogProductOption::find()->where(['product_id' => $id])->all();
                    if (!$options) {
                        $model->has_options = false;
                    }
                }
            }

            if (isset($_POST['AttributeForm']['tier-pricing']) && !empty($_POST['AttributeForm']['tier-pricing'])) {
                $tiers = $_POST['AttributeForm']['tier-pricing'];
                foreach ($tiers as $tier) {
                    $productTier = null;
                    if (array_key_exists('id', $tier)) { //existing option update
                        $productTier = CatalogProductTierPrice::findOne(['id' => $tier['id'], 'product_id' => $id, 'store_id' => CurrentStore::getStoreId()]);
                    }
                    if (!isset($productTier)) {
                        $productTier = new CatalogProductTierPrice();
                    }
                    $productTier->product_id = $id;
                    $productTier->store_id = CurrentStore::getStoreId();
                    $productTier->value = $tier['value'];
                    $productTier->qty = $tier['qty'];

                    if ($productTier->validate()) {
                        $productTier->save();
                    }
                }
            } else { //delete whats there
                CatalogProductTierPrice::deleteAll(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()]);
            }

            if (array_key_exists("Attribute", Yii::$app->request->post())) {
                $special = CatalogAttribute::findOne(["slug" => "special-price"]);
                foreach (Yii::$app->request->post('Attribute') as $vid => $attributeValue) {
                    // Is this a multiple select?
                    if (is_array($attributeValue)) {
                        // Remove the existing entries (update isn't possible
                        // unless we also track the previous value)
                        CatalogAttributeValue::deleteAll([
                            'attribute_id' => $vid,
                            'store_id' => CurrentStore::getStoreId(),
                            'product_id' => $id
                        ]);

                        foreach ($attributeValue as $singleAttributeValue) {
                            if ($vid == $special->id && strlen($singleAttributeValue) <= 0) {
                                $singleAttributeValue = "NULL";
                            }
                            $attributeValueModel = new CatalogAttributeValue();
                            $attributeValueModel->attribute_id = $vid;
                            $attributeValueModel->store_id = CurrentStore::getStoreId();
                            $attributeValueModel->product_id = $model->id;
                            $attributeValueModel->value = $singleAttributeValue;
                            $attributeValueModel->save();
                        }
                    } else {
                        $attributeValueModel = CatalogAttributeValue::findOne([
                            'attribute_id' => $vid,
                            'store_id' => CurrentStore::getStoreId(),
                            'product_id' => $id
                        ]);

                        // If no value exists for this attribute, or if the
                        // incoming value is different from the current
                        if (!isset($attributeValueModel) || $attributeValue != $attributeValueModel->value) {
                            if ($vid == $special->id && strlen($attributeValue) <= 0) {
                                $attributeValue = "NULL";
                            }
                            if ($attributeValueModel) {
                                $attributeValueModel->value = $attributeValue;
                                $attributeValueModel->update();
                            } else {
                                $attributeValueModel = new CatalogAttributeValue();
                                $attributeValueModel->attribute_id = $vid;
                                $attributeValueModel->store_id = CurrentStore::getStoreId();
                                $attributeValueModel->product_id = $model->id;
                                $attributeValueModel->value = $attributeValue;
                                $attributeValueModel->save();
                            }
                        }
                    }
                }
            }
            CatalogStoreProduct::deleteAll(['product_id' => $model->id]);
            if (array_key_exists('stores', $post)) {
                foreach (Yii::$app->request->post('stores') as $id => $value) {
                    $catalogStoreProduct = new CatalogStoreProduct();
                    $catalogStoreProduct->store_id = $id;
                    $catalogStoreProduct->product_id = $model->id;
                    $catalogStoreProduct->save(false);
                }
            }

            /**
             * @NOTE this is one way to do model relationships
             *
             *  use a non-db param in the model to hold temporary fields and then
             *  use a singular function to unset all relationships and set the ones
             *  that are included in the model loader. in theory, any time you update
             *  a multiselect/relationship you select the ones you want de-select the
             *  ones you don't want - so unlinking all and then relinking works out.
             */


            if ($model->load(Yii::$app->request->post())) {
                $model->saveFeatures();
            }

            $tab = "";
            if (isset($_POST['current-tab']) && !empty($_POST['current-tab'])) {
                $tab = $_POST['current-tab'];
            }

            return $this->redirect(["product/update/$model->id$tab"]);
        }

        // Categories
        $categories = CatalogCategory::findAll([
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
            'is_active' => true,
            'is_deleted' => false
        ]);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $categoriesArray[$category->id] = $category->name;
            }
        }

        // Brands
        if (CurrentStore::isNone()) {
            $brands = CatalogBrand::findAll([
                'store_id' => Store::NO_STORE,
                'is_active' => true,
                'is_deleted' => false
            ]);
        } else {
            $storeBrands = CatalogBrandStore::findAll([
                'store_id' => CurrentStore::getStoreId()
            ]);
            foreach ($storeBrands as $storeBrand) {
                $brandIds[] = $storeBrand->brand_id;
            }
            $brands = CatalogBrand::findAll([
                'id' => $brandIds,
                'is_active' => true,
                'is_deleted' => false
            ]);
        }
        if (!empty($brands)) {
            foreach ($brands as $brand) {
                $brandsArray[$brand->id] = $brand->name;
            }
        }

        // Attribute Sets, Attributes
        $attributes = [];
        $attributeIds = [];
        // Fetch all the attributes associated with attribute set $aid
        if (!empty($aid)) {
            $attributeSetAttributes = CatalogAttributeSetAttribute::findAll(['set_id' => $aid]);
//            echo '<pre>';die;
//            print_r($attributeSetAttributes); die;
            if (!empty($attributeSetAttributes)) {
                foreach ($attributeSetAttributes as $attributeSetAttribute) {
                    $attributeIds[] = $attributeSetAttribute->attribute_id;
                }
            }
        }
        // Fetch all default attributes paired with NO STORE
        $defaultAttributes = CatalogAttribute::find()->where([
            'store_id' => Store::NO_STORE, 'is_active' => true, 'is_deleted' => false, 'is_default' => true
        ])->orderBy(['sort' => SORT_ASC])->all();
        if (!empty($defaultAttributes)) {
            foreach ($defaultAttributes as $defaultAttribute) {
                $attributeIds[] = $defaultAttribute->id;
            }
        }
        // Deduplicate the resulting array and expand the result set
        // into objects grouped into sub-arrays by attribute category
        $attributeIds = array_unique($attributeIds);
        $attributeSetCategories = CatalogAttributeSetCategory::findAll([
            'is_active' => true, 'is_deleted' => false
        ]);

        if (!empty($attributeSetCategories)) {
            foreach ($attributeSetCategories as $attributeSetCategory) {
                $attributes[$attributeSetCategory->label] =
                    CatalogAttribute::find()->where([
                        'id' => $attributeIds,
                        'is_active' => true,
                        'is_deleted' => false,
                        'category_id' => $attributeSetCategory->id
                    ])->orderBy(['category_id' => SORT_ASC, 'sort' => SORT_ASC])->all();
            }
        }
//        echo '<pre>';
//        print_r($attributes); die;
        $productImages = [];
        $baseImageId = CatalogAttribute::findOne(['slug' => 'base-image'])->id;
        $productsBaseImages = CatalogAttributeValue::find()->where(['product_id' => $model->parent_id ? $model->parent_id : $id])->andWhere(['attribute_id' => $baseImageId])->all();
        if (!empty($productsBaseImages)) {
            foreach ($productsBaseImages as $productsBaseImage) {
                $productImages[] = $productsBaseImage['value'];
            }
        }

        return $this->render('update', [
            'model' => $model,
            'product_type' => $model->type,
            'category_ids' => $category_ids,
            'categoryProductModel' => $categoryProductModel,
            'catalogProductFeature' => $catalogProductFeature,
            'uploadForm' => $uploadForm,
            'categoriesArray' => isset($categoriesArray) ? $categoriesArray : [],
            'brandsArray' => isset($brandsArray) ? $brandsArray : [],
            'featuresArray' => $features,  //by using ArrayHelper::map() we don't need to check if it's set - it's always set as an array with differing count
            'featuresOptions' => $features_options,
            'attributes' => $attributes,
            'attributeSetId' => $aid ? $aid : null,
            'isUpdate' => true,
            'isOwner' => true,//$model->store_id == CurrentStore::getStoreId() ? true : null,
            'isChildSimple' => $model->type == CatalogProduct::CHILD_SIMPLE ? true : null,
            'isStandaloneSimple' => $model->type == CatalogProduct::SIMPLE ? true : null,
            'productImages' => $productImages
        ]);
    }

    public function actionImages($id)
    {

        $output['files'] = [];
        $directory = Yii::getAlias('@frontend/web/uploads/products');
        $files = FileHelper::findFiles($directory);
        foreach ($files as $file) {
            $fileName = basename($file);

            $inDb = CatalogImage::find()->Where(['file_name' => $fileName])->one();
            if (!$inDb) {
                $model = new CatalogImage();
                $model->file_name = $fileName;
                $model->created_at = time();
                $model->save();
            }
        }

        $searchModel = new CatalogImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 6;


        $productName = CatalogAttributeValue::storeValue('name', $id);

        return $this->render('images', [
            'model' => new CatalogImage(),
            'name' => $productName,
            'id' => $id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionSetDefaultImage()
    {
        if ($_POST == Yii::$app->request->post()) {

            if (isset($_POST['id']) && isset($_POST['product_id'])) {
                $baseImageId = CatalogAttribute::findOne(['slug' => 'base-image'])->id;
                $galleryImageId = CatalogAttribute::findOne(['slug' => 'media-gallery'])->id;
                $selects = CatalogProductGallery::find()->Where(['product_id' => $_POST['product_id'], 'attribute_id' => $baseImageId])->all();
                foreach ($selects as $select) {
                    $select->attribute_id = $galleryImageId;
                    $select->is_default = 0;
                    $select->save();
                }
                $newSelect = CatalogProductGallery::find()->Where(['id' => $_POST['id'], 'product_id' => $_POST['product_id']])->one();
                $newSelect->attribute_id = $baseImageId;
                $newSelect->is_default = 1;
                $newSelect->save();

                return true;
            }
        }
    }

    public function actionImageUpload()
    {
        $model = new CatalogProductGallery();

        $imageFile = UploadedFile::getInstance($model, 'value');

        $directory = Yii::getAlias('@frontend/web/uploads/products') . DIRECTORY_SEPARATOR;
        if (!is_dir($directory)) {
            FileHelper::createDirectory($directory);
        }

        if ($imageFile) {
            $fileName = str_replace(' ', '', $imageFile->name);
            $filePath = $directory . $fileName;


            if ($imageFile->saveAs($filePath)) {
                $model->value = $fileName;
                $model->attribute_id = CatalogAttribute::findOne(['slug' => 'media-gallery'])->id;
                $model->product_id = Yii::$app->request->get('id');
                $model->is_default = 0;
                $model->is_active = 1;
                $model->is_deleted = 0;
                $model->store_id = CurrentStore::getStoreId();
                $model->sort = 1;
                $model->save();

                $path = '/uploads/products' . DIRECTORY_SEPARATOR . $fileName;
                return Json::encode([
                    'files' => [
                        [
                            'name' => $fileName,
                            'size' => $imageFile->size,
                            'url' => $path,
                            'thumbnailUrl' => $path,
                            'deleteUrl' => '/admin/product/image-delete?name=' . $fileName,
                            'deleteType' => 'POST',
                        ],
                    ],
                ]);
            }
        }

        return '';
    }


    public function actionImageDelete($name)
    {
        $directory = Yii::getAlias('@frontend/web/uploads/products');
        if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
            unlink($directory . DIRECTORY_SEPARATOR . $name);
        }

        CatalogProductGallery::deleteAll(['value' => $name]);

        $files = FileHelper::findFiles($directory);
        $output = [];
        foreach ($files as $file) {
            $fileName = basename($file);
            $path = '/uploads/products' . DIRECTORY_SEPARATOR . $fileName;
            $output['files'][] = [
                'name' => $fileName,
                'size' => filesize($file),
                'url' => $path,
                'thumbnailUrl' => $path,
                'deleteUrl' => '/admin/product/image-delete?name=' . $fileName,
                'deleteType' => 'POST',
            ];
        }
        return Json::encode($output);
    }

    public function actionMedia($pid)
    {
        $model = CatalogProduct::findOne($pid);

        if ($model) {
            if (Yii::$app->request->post('value', false)) {
                $file = ImageManager::findOne([
                    'fileName' => Yii::$app->request->post('value')
                ]);

                if ($file) {
                    $fileExtension = substr($file->fileName, -3);
                    $attributeValueModel = CatalogAttributeValue::findOne([
                        'attribute_id' => CatalogAttribute::findOne(['slug' => 'base-image'])->id,
                        'store_id' => CurrentStore::getStoreId(),
                        'product_id' => $model->id
                    ]);

                    if (!isset($attributeValueModel)) {
                        $attributeValueModel = new CatalogAttributeValue();
                        $attributeValueModel->attribute_id = CatalogAttribute::findOne(['slug' => 'base-image'])->id;
                        $attributeValueModel->store_id = CurrentStore::getStoreId();
                        $attributeValueModel->product_id = $model->id;
                        $attributeValueModel->value = $file->id . "_$file->fileHash.$fileExtension";
                        if ($attributeValueModel->save()) {
                            $galleryImage = new CatalogProductGallery();
                            $galleryImage->attribute_id = CatalogAttribute::findOne(['slug' => 'base-image'])->id;
                            $galleryImage->store_id = CurrentStore::getStoreId();
                            $galleryImage->value = $file->id . "_$file->fileHash.$fileExtension";
                            $galleryImage->product_id = $model->id;
                            $galleryImage->is_default = true;
                            $galleryImage->is_active = true;
                            $galleryImage->created_at = time();
                            $galleryImage->save();
                        }
                    } else {
                        $attributeValueModel->value = $file->id . "_$file->fileHash.$fileExtension";
                        $attributeValueModel->update();
                    }

                    //Clear Product Cache
                    shell_exec('redis-cli flushall');

                    $cache = Yii::$app->cache;
                    $key = 'products_' . CurrentStore::getStoreId();
                    $cache->delete($key);

                    $this->redirect(Url::to(['update', 'id' => $model->id]));
                }

            }

            return $this->render('media', [
                'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post["id"])) {
                $id = $post["id"];
                $storeId = CurrentStore::getStoreId();
                if ($storeId !== null) {
                    if ($product = CatalogProduct::findOne(['id' => $id, 'store_id' => $storeId, 'is_deleted' => false, 'is_active' => true])) {
                        if ($options = CatalogProductOption::findAll(['product_id' => $id, 'store_id' => $storeId])) {
                            foreach ($options as $option) {
                                CatalogProductOptionValue::deleteAll(['option_id' => $option->option_id]);
                                $option->delete();
                            }
                        }
                        CatalogProductRelation::deleteAll(["product_id_1" => $id]);
                        CatalogProductAttachment::deleteAll(["product_id" => $id]);
                        CatalogProductAttributeSet::deleteAll(["product_id" => $id]);
                        CatalogProductFeature::deleteAll(["product_id" => $id]);
                        if ($this->findModel($id)->delete()) {
                            if ($stores = Store::findAll(['is_deleted' => false, 'is_active' => true])) {
                                foreach ($stores as $store) {
                                    if (CatalogStoreProduct::findOne(['store_id' => $store->id, 'product_id' => $product->id])) {
                                        $data_string = "private_key=$store->searchanise_private_key&id=$id";
                                        $ch = curl_init("https://www.searchanise.com/api/items/delete/json");
                                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $result = curl_exec($ch);
                                    }
                                }
                            }
                            CatalogStoreProduct::deleteAll(["product_id" => $id]);
                        }
                        return $this->redirect(Url::to(['index']));
                    }
                }
            }
        }
    }

    public function actionBulkAction()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post['products'])) {

                foreach ($post['products'] as $id) {
                    switch ($post['action']) {
                        case 'active':
                            $model = new ProductGrid();
                            $model->switch_id = $id;
                            $model->switch_key = "active";
                            $model->switch_value = 1;
                            $model->updateAttribute();
                            break;
                        case 'inactive':
                            $model = new ProductGrid();
                            $model->switch_id = $id;
                            $model->switch_key = "active";
                            $model->switch_value = 0;
                            $model->updateAttribute();
                            break;
                        case 'delete':
                            if ($storeId = CurrentStore::getStoreId() === "0") {
                                $options = CatalogProductOption::findAll(['product_id' => $id, 'store_id' => CurrentStore::getStoreId()]);
                                foreach ($options as $option) {
                                    CatalogProductOptionValue::deleteAll(['option_id' => $option->option_id]);
                                    $option->delete();
                                }
                                CatalogProductRelation::deleteAll(["product_id_1" => $id]);
                                CatalogProductAttachment::deleteAll(["product_id" => $id]);
                                CatalogProductAttributeSet::deleteAll(["product_id" => $id]);
                                CatalogProductFeature::deleteAll(["product_id" => $id]);
                                CatalogStoreProduct::deleteAll(["product_id" => $id]);
                                $this->findModel($id)->delete();
                            } else {
                                CatalogStoreProduct::deleteAll(["product_id" => $id, 'store_id' => $storeId]);
                            }
                            break;
                    }
                }
                return "success";
            }
        }
    }

    public function actionJson()
    {
        if (Yii::$app->request->isAjax) {
            //get products
            $products = CatalogProduct::find()->productGrid();

            //get attributes
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
              SELECT *
              FROM catalog_attribute
              WHERE slug IN ('price', 'special-price', 'sku', 'name', 'mattress-size', 'active')
            ");
            $attributes = $command->queryAll();

            //get products columns
            $output = [];
            foreach ($products as $product) {
                $temp_arr = [];

                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);

                $price = CatalogProduct::filterAttributeValuesByKey('price', $attributes, $attribute_values);
                $special_price = CatalogProduct::filterAttributeValuesByKey('special-price', $attributes, $attribute_values);


                $temp_arr['id'] = $product['id'];
                $temp_arr['set'] = $this->findSetByProduct($product['id']);
                $temp_arr['sku'] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $temp_arr['type'] = $product['type'];
                $temp_arr['name'] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $temp_arr['size'] = CatalogProduct::filterAttributeValuesByKey('mattress-size', $attributes, $attribute_values);
                $temp_arr['brand'] = CatalogProduct::findBrandByProduct($product['brand_id']);
                $temp_arr['price'] = $price ? floatval($price) : 0;
                $temp_arr['special-price'] = $special_price ? floatval($special_price) : 0;
                $temp_arr['active'] = CatalogProduct::filterAttributeValuesByKey('active', $attributes, $attribute_values) ? true : false;

                array_push($output, $temp_arr);
            }

            return Json::encode($output);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    private function findSetByProduct($product_id)
    {
        $current_store_id = CurrentStore::getStoreId();
        if (!$current_store_id) {
            $current_store_id = 0;
        }

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
          SELECT catalog_attribute_set.*
          FROM catalog_attribute_set
          LEFT JOIN catalog_product_attribute_set ON catalog_attribute_set.id = catalog_product_attribute_set.set_id
          WHERE catalog_product_attribute_set.store_id IN (0, $current_store_id)
          AND catalog_product_attribute_set.product_id = $product_id
          ORDER BY catalog_product_attribute_set.store_id DESC
          LIMIT 1;
        ");
        $result = $command->queryAll();

        if (count($result) > 0) {
            return $result[0]['label'];
        }
        return null;
    }

    private function findProductAttributeValue($attribute_slug, $product_id)
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
          SELECT *
          FROM catalog_attribute
          WHERE slug = '$attribute_slug'
          LIMIT 1
        ");
        $attribute = $command->queryAll();

        if (count($attribute) > 0) {
            $attribute = $attribute[0];

            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
              SELECT *
              FROM catalog_attribute_value
              WHERE attribute_id = '" . $attribute['id'] . "'
              AND store_id IN ('" . Store::NO_STORE . "', '" . CurrentStore::getStoreId() . "')
              AND product_id = '" . $product_id . "'
              ORDER BY store_id DESC
              LIMIT 1
            ");
            $attribute_value = $command->queryAll();


            if (count($attribute_value) > 0) {
                $attribute_value = $attribute_value[0];
                if ($attribute['type_id'] == 2) {
                    $connection = Yii::$app->getDb();
                    $command = $connection->createCommand("
                      SELECT value
                      FROM catalog_attribute_option
                      WHERE id = '" . $attribute_value['value'] . "'
                    ");
                    $attribute_option = $command->queryAll();

                    if (count($attribute_option) > 0) {
                        return $attribute_option[0]['value'];
                    }
                } else {
                    return $attribute_value['value'];
                }
            }


        }

        return null;
    }

    public function actionGetBrands()
    {

    }

    public function actionUpdateBulk()
    {
        $model = new ProductGrid();
        $model->load(Yii::$app->request->post());
        shell_exec('redis-cli flushall');
        return $model->bulkUpdate();
    }

    public function actionUpdateAttribute()
    {
        $model = new ProductGrid();
        $model->load(Yii::$app->request->post());
        shell_exec('redis-cli flushall');
        return $model->updateAttribute();
    }

    public function actionJsonDelete()
    {
        $model = new ProductGrid();
        $model->load(Yii::$app->request->post());
        shell_exec('redis-cli flushall');
        return $model->deleteSingle();
    }


    /**
     * This function accepts ajax posts when check boxes are checked and unchecked on the product/update pages tab: Associated Products
     */

    public function actionProductRelationAjax()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post["isChecked"]) && !empty($post["isChecked"]) && isset($post["id1"]) && !empty($post["id1"]) && isset($post["id2"]) && !empty($post["id2"]) && isset($post["type"]) && !empty($post["type"])) {
                $post["isChecked"] = ($post["isChecked"] === "true") ? true : false;
                if ($post["type"] == "associated") {
                    if ($post["isChecked"]) {
                        $catalogProduct = CatalogProduct::find()->Where(['id' => $post["id2"]])->one();
                        $catalogProduct->parent_id = $post["id1"];
                        $catalogProduct->save();

                    } else {
                        $catalogProduct = CatalogProduct::find()->Where(['id' => $post["id2"]])->one();
                        $catalogProduct->parent_id = null;
                        $catalogProduct->save();
                    }
                } else {
                    $type = CatalogProductRelationType::find()->Where(['id' => $post["type"]])->one();
                    if (!$type) {
                        return Json::encode("relation type dose not exist");
                    }
                    $post['type'] = $type->type_name;
                    if ($post["isChecked"]) {
                        $relation = CatalogProductRelation::find()->Where(["product_id_1" => $post["id1"]])->andWhere(["product_id_2" => $post["id2"]])->andWhere(["type_id" => $type->id])->one();
                        if (!$relation) {
                            $relation = new CatalogProductRelation();
                        }
                        $relation->product_id_1 = $post["id1"];
                        $relation->product_id_2 = $post["id2"];
                        $relation->type_id = $type->id;
                        $relation->save();
                    } else {
                        $relation = CatalogProductRelation::find()->Where(["product_id_1" => $post["id1"]])->andWhere(["product_id_2" => $post["id2"]])->andWhere(["type_id" => $type->id])->one();
                        if ($relation) {
                            $relation->delete();
                        }
                    }
                }
                return Json::encode($post['type'] . " product association success");
            }

            if (isset($post["switch_key"])) {
                $success = false;
                if ($post["switch_key"] == "sort") {
                    if ($attribute_id = CatalogAttribute::findOne(['slug' => 'associated-product-sort'])) {
                        $attribute_id = $attribute_id->id;
                        $sortAttribute = CatalogAttributeValue::findOne(["attribute_id" => $attribute_id, "product_id" => $post['pid'], "store_id" => CurrentStore::getStoreId()]);
                        if (empty($post["switch_value"])) {
                            $sortAttribute->delete();
                            $success = true;
                        } else {
                            if (!$sortAttribute) {
                                $sortAttribute = new CatalogAttributeValue();
                                $sortAttribute->store_id = CurrentStore::getStoreId();
                                $sortAttribute->product_id = $post['pid'];
                                $sortAttribute->attribute_id = $attribute_id;
                            }
                            $sortAttribute->value = $post["switch_value"];
                            $sortAttribute->save();
                        }
                        if ($success) {
                            return "<div class=\"input-group\"><input class=\"form-control sort-edit sort\" pid=\"" . $post['pid'] . "\" type=\"number\" value=\"" . $post["switch_value"] . "\"></div>";
                        }
                    }
                }
            }

            return Json::encode("product association failed");
        }

        return $this->redirect(['index']);
    }

    /**
     * This function accepts ajax posts when check boxes are checked and unchecked on the product/update pages attachment tab: Associated Attachements
     * and the attachment/update pages Associated Products
     */
    public
    function actionAssociateProductToAttachment()
    {
        if ($post = Yii::$app->request->post()) {
            if (isset($post["isChecked"]) && !empty($post["isChecked"]) && isset($post["product_id"]) && !empty($post["product_id"]) && isset($post["attachment_id"]) && !empty($post["attachment_id"])) {
                $relation = CatalogProductAttachment::find()->Where(["product_id" => $post["product_id"]])->andWhere(["attachment_id" => $post["attachment_id"]])->one();
                if (!$relation && $post["isChecked"] == true) {
                    $relation = new CatalogProductAttachment();
                    $relation->product_id = $post["product_id"];
                    $relation->attachment_id = $post["attachment_id"];
                    $relation->save();
                    return Json::encode("create associate attachment success");
                } else {
                    $relation->delete();
                    return Json::encode("delete associate attachment success");
                }
            }
            return Json::encode("failed to associate attachment");
        }
        return $this->redirect(['index']);
    }


    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = CatalogProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public
    function actionAssociateDescriptions()
    {
        $descriptions = Descriptions::find()->all();
        foreach ($descriptions as $description) {
            $product = CatalogProduct::findBySku($description->sku);

            if ($product) {
                $description_id = CatalogAttribute::getAttributeBySlug('description')->id;
                $short_description_id = CatalogAttribute::getAttributeBySlug('short-description')->id;

                $catalog_attribute_value = CatalogAttributeValue::find()->where([
                    'store_id' => 0,
                    'attribute_id' => $description_id,
                    'product_id' => $product->id

                ])->count();

                if (!$catalog_attribute_value) {
                    $catalog_attribute_value = new CatalogAttributeValue();
                    $catalog_attribute_value->product_id = $product->id;
                    $catalog_attribute_value->store_id = 0;
                    $catalog_attribute_value->value = ($description->description) ? $description->description : '';
                    $catalog_attribute_value->attribute_id = $description_id;
                    $catalog_attribute_value->save(false);
                }


                $catalog_attribute_value = CatalogAttributeValue::find()->where([
                    'store_id' => 0,
                    'attribute_id' => $short_description_id,
                    'product_id' => $product->id
                ])->count();

                if (!$catalog_attribute_value) {
                    $catalog_attribute_value = new CatalogAttributeValue();
                    $catalog_attribute_value->product_id = $product->id;
                    $catalog_attribute_value->store_id = 0;
                    $catalog_attribute_value->attribute_id = $short_description_id;
                    $catalog_attribute_value->value = ($description->short_description) ? $description->short_description : '';
                    $catalog_attribute_value->save(false);
                }
            }
        }
    }

    public
    function actionAssociateBrandCategories()
    {
        $products = CatalogProduct::find()->all();

        foreach ($products as $product) {

            $brand = CatalogBrand::findOne($product->brand_id);
            if ($brand) {
                $category = CatalogCategory::find()->where(['slug' => $brand->slug])->one();

                if ($category) {
                    $catalog_category = CatalogCategoryProduct::find()->where([
                        'category_id' => $category->id,
                        'product_id' => $product->id
                    ])->count();

                    if (!$catalog_category) {
                        $catalog_category = new CatalogCategoryProduct();
                        $catalog_category->product_id = $product->id;
                        $catalog_category->category_id = $category->id;
                        $catalog_category->created_at = time();
                        $catalog_category->save(false);
                    }
                }
            }
        }
    }

    public
    function actionReassignOptions()
    {
        $options = CatalogProductOption::find()->all();

        foreach ($options as $option) {

            $product = CatalogProduct::findBySku($option->sku);

            if ($product) {
                $option->product_id = $product->id;
                if ($option->save(false)) {
                    $product->has_options = true;
                    $product->save(false);
                }

            }

        }
    }

    public function actionRefresh()
    {

//        $categories = CatalogCategory::find()->all();
//        foreach($categories as $category){
//            if(CurrentStore::getStoreId() > 0){
//                Yii::$app->cache->delete('category_products_'.$category->slug.'_'.CurrentStore::getStoreId());
//                Cache::warmCache(CurrentStore::getStoreId());
//            }else{
//                foreach (Store::find()->where(['is_active'=>1])->all() as $store){
//                    Yii::$app->cache->delete('category_products_mattresses_'.$store->id);
//                }
//                Cache::warmCache();
//            }
//        }
        shell_exec('redis-cli flushall');


        Yii::$app->session->setFlash('success', 'Store Product Cache has been flushed');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionServerSide()
    {
        if (Yii::$app->request->isAjax) {
            // Initialize
            $request = Yii::$app->request->get();

            $session = Yii::$app->session;
            $productSearch = NULL;
            if (isset($session['productSearch'])) {
                $productSearch = $session['productSearch'];
            }

            $columns = [
                null,
                'p.brand_id',
                'p.type',
                'sku.value',
                'name.value',
                'CAST(price.value AS DECIMAL(10,2))',
                'CAST(special.value AS DECIMAL(10,2))',
                'active.value',
            ];
            $order = "";
            $order_column = "";
            if (isset($request['order'][0]['column'])) {
                $order_column = $columns[$request['order'][0]['column']];
                $order_direction = $request['order'][0]['dir'];
                $order = "ORDER BY $order_column $order_direction";
            }

            $offset = "";
            if (isset($request['start'])) {
                $offset = $request['start'] . ", ";
            }
            $group = "GROUP BY p.id";
            $limit = "";
            if (isset($request['length'])) {
                $limit .= " LIMIT $offset " . $request['length'];
                $productSearch['page'] = $request['start'] / $request['length'];
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
                $join1 .= "JOIN `catalog_attribute_value` AS cav ON cav.`product_id` = p.`id`";
                $countWhere1 = $where1 = "WHERE (p.`id` IN (
                            SELECT `product_id`
                            FROM   `catalog_store_product`
                            WHERE  `store_id` = '$current_store_id'))";
            }

            $column = 1;
            $productSearch['brand'] = $request['columns'][$column]['search']['value'];
            if ($request['columns'][$column]['search']['value'] > 0) {
                $wheres .= " AND p.brand_id = '" . $request['columns'][1]['search']['value'] . "' ";
            }

            $column++;
            $productSearch['type'] = $request['columns'][$column]['search']['value'];
            if (strlen($request['columns'][2]['search']['value']) > 1) {
                $wheres .= " AND p.type = '" . $request['columns'][$column]['search']['value'] . "'";
            }

            $column++;
            $productSearch['sku'] = $request['columns'][$column]['search']['value'];
            if (($skuLength = (strlen($request['columns'][$column]['search']['value']) > 0) ? true : false) || $order_column == $columns[$column]) {
                $joins .= " JOIN catalog_attribute_value as sku ON p.id = sku.product_id AND sku.attribute_id = '4' AND (sku.store_id = $current_store_id OR  sku.store_id = 0)";
                if ($skuLength) {
                    $joins .= " AND sku.value LIKE '%" . addslashes($request['columns'][$column]['search']['value']) . "%'";
                }
            }

            $column++;
            $productSearch['name'] = $request['columns'][$column]['search']['value'];
            if (($nameLength = (strlen($request['columns'][$column]['search']['value']) > 0) ? true : false) || $order_column == $columns[$column]) {
                $joins .= " JOIN catalog_attribute_value as name ON p.id = name.product_id AND name.attribute_id = '1' AND (name.store_id = $current_store_id OR name.store_id = 0)";
                if ($nameLength) {
                    $joins .= " AND name.value LIKE '%" . addslashes($request['columns'][$column]['search']['value']) . "%'";
                }
            }

            $column++;
            $productSearch['price'] = $request['columns'][$column]['search']['value'];
            if (($priceLength = (strlen($request['columns'][$column]['search']['value']) > 3) ? true : false) || $order_column == $columns[$column]) {
                $joins .= " JOIN catalog_attribute_value as price ON p.id = price.product_id AND price.attribute_id = '7' AND (price.store_id = $current_store_id OR price.store_id = 0)";
                if ($priceLength) {
                    $priceRange = json_decode($request['columns'][$column]['search']['value']);
                    $priceLow = (isset($priceRange->low) && !empty($priceRange->low) && is_numeric($priceRange->low));
                    $priceHigh = (isset($priceRange->high) && !empty($priceRange->high) && is_numeric($priceRange->high));
                    if ($priceLow && !$priceHigh) {
                        $joins .= " AND price.value > " . addslashes($priceRange->low);
                    }
                    if ($priceHigh && !$priceLow) {
                        $joins .= " AND price.value < " . addslashes($priceRange->high);
                    }
                    if ($priceLow && $priceHigh) {
                        $joins .= " AND price.value BETWEEN " . addslashes("$priceRange->low AND $priceRange->high");
                    }
                }
            }

            $column++;
            $productSearch['special'] = $request['columns'][$column]['search']['value'];
            if (($specialLength = (strlen($request['columns'][$column]['search']['value']) > 3) ? true : false) || $order_column == $columns[$column]) {
                $joins .= " JOIN catalog_attribute_value as special ON p.id = special.product_id AND special.attribute_id = '27' AND (special.store_id = $current_store_id OR special.store_id = 0)";
                if ($specialLength) {
                    $priceRange = json_decode($request['columns'][$column]['search']['value']);
                    $priceLow = (isset($priceRange->low) && !empty($priceRange->low) && is_numeric($priceRange->low));
                    $priceHigh = (isset($priceRange->high) && !empty($priceRange->high) && is_numeric($priceRange->high));
                    if ($priceLow && !$priceHigh) {
                        $joins .= " AND special.value > " . addslashes($priceRange->low);
                    }
                    if ($priceHigh && !$priceLow) {
                        $joins .= " AND special.value < " . addslashes($priceRange->high);
                    }
                    if ($priceLow && $priceHigh) {
                        $joins .= " AND special.value BETWEEN " . addslashes("$priceRange->low AND $priceRange->high");
                    }
                }
            }

            $column++;
            $productSearch['active'] = $request['columns'][$column]['search']['value'];
            if (($activeLength = ($request['columns'][$column]['search']['value'] > 0) ? true : false) || $order_column == $columns[$column]) {
                $joins .= " JOIN catalog_attribute_value as active ON p.id = active.product_id AND active.attribute_id = '33' AND active.store_id = $current_store_id";
                if ($activeLength) {
                    if ($request['columns'][$column]['search']['value']) {
                        $value = $request['columns'][$column]['search']['value'];
                    } elseif (isset($productSearch['active'])) {
                        $value = $productSearch['active'];
                    }
                    if ($value == '2') {
                        $value = '0';
                    }
                    $joins .= " AND active.value = '$value'";
                }
            }
            Yii::$app->session->set('productSearch', $productSearch);
            //print_r("$select $froms $join1 $joins $where1 $wheres $group $order $limit");die;
            $command = $connection->createCommand("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");
            $products = $command->queryAll();
            $data = [];
            $connection = Yii::$app->getDb();

            $attributes = CatalogAttribute::find()
                ->where(['in', 'slug', ["price", "special-price", "sku", "name", "active"]])
                ->asArray()
                ->all();

            foreach ($products as $product) {
                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
                $row = [];
                $productCheckbox = ' <input type="checkbox" class="productselector" name="selectProduct[]" data-id="' . $product['id'] . '">';
                $row[] = $productCheckbox;
                $row[] = CatalogProduct::findBrandByProduct($product['brand_id']);
                $row[] = $product['type'];
                $row[] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $row[] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $price = CatalogProduct::filterAttributeValuesByKey('price', $attributes, $attribute_values);
                $special_price = CatalogProduct::filterAttributeValuesByKey('special-price', $attributes, $attribute_values);
                $row[] = "<div class='input-group'><span class='input-group-addon'>$</span><input class='form-control price-edit price' productId ='" . $product['id'] . "' storeId='" . $current_store_id . "' type='number' value ='" . number_format($price ? floatval($price) : 0, 2, '.', '') . "'/></div>";
                $row[] = "<div class='input-group'><span class='input-group-addon'>$</span><input class='form-control price-edit special' productId ='" . $product['id'] . "' storeId='" . $current_store_id . "' type='number' value ='" . number_format($special_price ? floatval($special_price) : 0, 2, '.', '') . "'/></div>";
                $checked = CatalogProduct::filterAttributeValuesByKey('active', $attributes, $attribute_values) ? "checked" : "";
                $row[] = "<label class='switch'><input productId='" . $product['id'] . "' storeId='" . $current_store_id . "' type='checkbox' class='product_grid_switch' $checked><div class='slider round'></div></label>";
                $row[] = '<a href="/admin/product/update/' . $product["id"] . '"><i class="material-icons">edit</i></a>';
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

    public function actionServerSideAssociated()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->get();
            $columns = ["p.id", 'name.value', 'sku.value', 'p.brand_id', 'sort.value'];
            $order = "ORDER BY cast(sort.value as unsigned) asc";
            $order_column = "sort.value";
            if (isset($request['order'][0]['column'])) {
                $order_column = $columns[$request['order'][0]['column']];
                $order_direction = $request['order'][0]['dir'];
                if ($order_column === "p.id") {
                    $order = "order by case when p.parent_id = " . $request['pid'] . " then -1 else p.id end $order_direction";
                }
                if ($order_column === "sort.value") {
                    $order = "ORDER BY cast(sort.value as unsigned) $order_direction";
                } else {
                    $order = "ORDER BY $order_column $order_direction";
                }
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
                $countWhere1 = $where1 = "WHERE p.store_id = 0 AND p.id != " . $request['pid'];
            } else {
                $join1 .= "LEFT JOIN `catalog_attribute_value` AS cav ON cav.`product_id` = p.`id`";
                $countWhere1 = $where1 = "WHERE (p.`id` IN (
                            SELECT `product_id`
                            FROM   `catalog_store_product`
                            WHERE  `store_id` = '$current_store_id')) AND p.id != " . $request['pid'];
            }

            $column = 0;

            if ($request['columns'][$column]['search']['value'] == "") {
                $request['columns'][$column]['search']['value'] = "1";
            }

            if (strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                if ($request['columns'][$column]['search']['value'] !== '0') {
                    $yesNo = ($request['columns'][$column]['search']['value'] === '1') ? "= " . $request['pid'] : "IS NULL";
                    $wheres .= " AND p.parent_id $yesNo";
                }
            }

            $column++;
            if ($nameLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                $joins .= "LEFT JOIN catalog_attribute_value as name ON p.id = name.product_id AND name.attribute_id = 1 AND (name.store_id = $current_store_id OR name.store_id = 0)";
                if ($nameLength) {
                    $wheres .= " AND name.value LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
                }
            }
            $column++;
            if ($skuLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                $joins .= "LEFT JOIN catalog_attribute_value as sku ON p.id = sku.product_id AND sku.attribute_id = 4 AND (sku.store_id = $current_store_id OR sku.store_id = 0)";
                if ($skuLength) {
                    $wheres .= " AND sku.value LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
                }
            }
            $column++;
            if ($request['columns'][$column]['search']['value'] > 0) {
                $wheres .= " AND p.brand_id = '" . $request['columns'][$column]['search']['value'] . "' ";
            }

            $column++;
            if ($request['columns'][$column]['search']['value'] > 0 || $order_column == $columns[$column]) {
                $joins .= "LEFT JOIN catalog_attribute_value as sort ON p.id = sort.product_id AND sort.attribute_id = 66 AND (sort.store_id = $current_store_id OR sort.store_id = 0)";
            }

            //print_r("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");die;

            $command = $connection->createCommand("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");
            $products = $command->queryAll();
            $connection = Yii::$app->getDb();

            $attributes = CatalogAttribute::find()
                ->where(['in', 'slug', ["price", "special-price", "sku", "name", "active", "associated-product-sort"]])
                ->asArray()
                ->all();

            $associatedProducts = CatalogProduct::find()->where(["parent_id" => $request['pid']])->asArray()->all();
            $associatedProducts = ArrayHelper::getColumn($associatedProducts, "id");
            $data = [];
            foreach ($products as $product) {
                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
                $row = [];
                $checked = (in_array($product['id'], $associatedProducts)) ? "checked" : "";
                $row[] = "<input type='checkbox' class='kv-row-checkbox' name='selection[]' value='3' href='/admin/product/product-relation-ajax' id1='" . $request['pid'] . "' id2='" . $product['id'] . "' relationtype='associated' $checked>";
                $row[] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $row[] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $row[] = CatalogProduct::findBrandByProduct($product['brand_id']);
                $row[] = ($checked) ? "<div class='input-group'><input class='form-control sort-edit sort' data-pid='" . $product['id'] . "' type='text' value ='" . CatalogProduct::filterAttributeValuesByKey('associated-product-sort', $attributes, $attribute_values) . "'/></div>" : "";
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

    public function actionServerSideRelationship()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->get();
            $columns = ["relation.id", 'name.value', 'sku.value', 'p.brand_id'];
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
                $countWhere1 = $where1 = "WHERE p.store_id = 0";
            } else {
                $join1 .= "LEFT JOIN `catalog_attribute_value` AS cav ON cav.`product_id` = p.`id`";
                $countWhere1 = $where1 = "WHERE (p.`id` IN (
                            SELECT `product_id`
                            FROM   `catalog_store_product`
                            WHERE  `store_id` = '$current_store_id'))";
            }

            $type_id = $request['type'];
            $column = 0;

            if ($request['columns'][$column]['search']['value'] == "") {
                $request['columns'][$column]['search']['value'] = "1";
            }

            if (strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == "relation.id") {
                $joins .= "LEFT JOIN catalog_product_relation as relation ON p.id = relation.product_id_2 AND relation.product_id_1 = '" . $request['pid'] . "' AND relation.type_id = '$type_id'";
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

            $relatedProducts = ArrayHelper::getColumn(CatalogProductRelation::find()->where(["type_id" => $type_id, "product_id_1" => $request['pid']])->asArray()->all(), "product_id_2");

            foreach ($products as $product) {
                if ($request['pid'] == $product['id']) {
                    continue;
                }
                $attribute_values = CatalogProduct::findProductAttributeValues($product['id'], $attributes);
                $row = [];
                $checked = (in_array($product['id'], $relatedProducts)) ? "checked" : "";
                $row[] = "<input type='checkbox' class='kv-row-checkbox' name='selection[]' value='3' href='/admin/product/product-relation-ajax' id1='" . $request['pid'] . "' id2='" . $product['id'] . "' relationtype='" . $request['type'] . "' $checked>";
                $row[] = CatalogProduct::filterAttributeValuesByKey('name', $attributes, $attribute_values);
                $row[] = CatalogProduct::filterAttributeValuesByKey('sku', $attributes, $attribute_values);
                $row[] = CatalogProduct::findBrandByProduct($product['brand_id']);
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

    public function actionServerSideAttachment()
    {
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request->get();
            $columns = ["cpa.id", 'a.attachment_id', 'a.title', 'a.file_name'];
            if (isset($request['order'][0]['column'])) {
                $order_column = $columns[$request['order'][0]['column']];
                $order_direction = $request['order'][0]['dir'];
                $order = "ORDER BY $order_column $order_direction";
            } else {
                $order = "ORDER BY cpa.id desc";
                $order_column = "cpa.id";
            }

            $offset = "";
            if (isset($request['start'])) {
                $offset = $request['start'] . ", ";
            }
            $group = "GROUP BY a.attachment_id";

            $limit = "";
            if (isset($request['length'])) {
                $limit .= " LIMIT $offset " . $request['length'];
            }

            $current_store_id = CurrentStore::getStoreId();
            if ($current_store_id == null) {
                $current_store_id = 0;
            }

            $connection = Yii::$app->getDb();
            $select = "SELECT a.*, cpa.product_id product_id";
            $froms = " FROM catalog_attachment a ";
            $join1 = "";
            $joins = "";
            $wheres = "";
            $joins .= "LEFT JOIN catalog_product_attachment as cpa ON a.attachment_id = cpa.attachment_id AND cpa.product_id = '" . $request['pid'] . "'";
            $setFilter = false;
            $countWhere1 = $where1 = "WHERE a.store_id = $current_store_id";

            $column = 0;

            if ($request['columns'][$column]['search']['value'] == "") {
                $request['columns'][$column]['search']['value'] = "1";
            }

            if (strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                if ($request['columns'][$column]['search']['value'] !== '0') {
                    $yesNo = ($request['columns'][$column]['search']['value'] === '1') ? "IS NOT NULL" : "IS NULL";
                    $wheres .= " AND cpa.id $yesNo";
                }
            }
            $column++;
            if ($nameLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                $wheres .= " AND $columns[$column] = " . $request['columns'][$column]['search']['value'] . " ";
            }
            $column++;
            if ($skuLength = strlen($request['columns'][$column]['search']['value']) > 0 || $order_column == $columns[$column]) {
                $wheres .= " AND $columns[$column] LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
            }
            $column++;
            if ($request['columns'][$column]['search']['value'] > 0) {
                $wheres .= " AND $columns[$column] LIKE '%" . $request['columns'][$column]['search']['value'] . "%'";
            }

            $command = $connection->createCommand("$select $froms $join1 $joins $where1 $wheres $group $order $limit;");
            $attachments = $command->queryAll();
            $data = [];

            $relatedAttachments = CatalogProductAttachment::find()->where(["product_id" => $request['pid']])->asArray()->all();
            $relatedAttachments = ArrayHelper::getColumn($relatedAttachments, "product_id");
            foreach ($attachments as $attachment) {
                $row = [];
                $checked = (in_array($attachment['product_id'], $relatedAttachments)) ? "checked" : "";
                $row[] = "<input type='checkbox' class='kv-row-checkbox' name='selection[]' value='" . $attachment['attachment_id'] . "' href='/admin/product/associate-product-to-attachment' data-pid='" . $request['pid'] . "' attachment_id='" . $attachment['attachment_id'] . "' $checked>";
                $row[] = $attachment['attachment_id'];
                $row[] = $attachment['title'];
                $row[] = $attachment['file_name'];
                $data[] = $row;
            }

            $countSelect = "SELECT a.*";

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


    public function actionAddProductImages()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $media = MediaTemp::find()->all();
        foreach ($media as $m) {
            $product = CatalogProduct::findBySku($m->sku);
            if ($product) {
                $galleryImage = new CatalogProductGallery();
                $galleryImage->attribute_id = '57';
                $galleryImage->value = $m->value;
                $galleryImage->store_id = 0;
                $galleryImage->product_id = $product->id;
                $galleryImage->is_deleted = 0;
                $galleryImage->created_at = time();
                $galleryImage->save(false);
            }
        }
    }

    public function actionCorrectProductImages()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);
        $images = CatalogProductGallery::find()->where(['attribute_id' => '30'])->all();
        foreach ($images as $image) {
            if ($image) {
                $galleryImages = CatalogProductGallery::find()->where(['product_id' => $image->product_id, 'attribute_id' => '57', 'value' => $image->value])->all();
                foreach ($galleryImages as $gi) {
                    $gi->delete();

                }
            }
        }
    }

    public
    function actionFixAttachments()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 3000);
        $attachments = CatalogProductAttachment::find()->all();

        foreach ($attachments as $attachment) {
            $product = CatalogProduct::findBySku($attachment->sku);
            if ($product) {
                $attachment->product_id = $product->id;
                $attachment->save(false);
            }
        }
    }

    public
    function actionFixTierPricing()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 3000);
        $prices = CatalogProductTierPrice::find()->all();

        foreach ($prices as $price) {
            $product = CatalogProduct::findBySku($price->sku);
            if ($product) {
                $price->product_id = $product->id;
                $price->found = true;
                $price->save(false);
            }
        }
    }

    public function actionFixMeta()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 3000);
        $metaData = CatalogProductMetaData::find()->all();

        foreach ($metaData as $meta) {
            $product = CatalogProduct::findBySku($meta->sku);
            if ($product) {
                $keywordAttribute = CatalogAttribute::getAttributeBySlug('meta-keywords');
                if ($keywordAttribute) {
                    $attributeValue = new CatalogAttributeValue();
                    $attributeValue->attribute_id = $keywordAttribute->id;
                    $attributeValue->product_id = $product->id;
                    $attributeValue->store_id = 0;
                    $attributeValue->value = $meta->keywords;
                    $attributeValue->save(false);
                }
            }
        }
    }

    public function actionTinymceupload($id)
    {
        $imageFolder = Yii::getAlias('@frontend/web/uploads/products/');
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {

            if (isset($_SERVER['HTTP_ORIGIN'])) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return false;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return false;
            }

            //remove spaces
            $temp['name'] = preg_replace('/\s+/', '_', $temp['name']);

            // Accept upload if there was no origin, or if it is an accepted origin
            $fileToWrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $fileToWrite);

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            return json_encode(array('location' => $temp['name']));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
            return false;
        }
    }
}

