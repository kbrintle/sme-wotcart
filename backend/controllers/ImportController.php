<?php

namespace backend\controllers;

use common\models\catalog\CatalogCategoryMagento;
use common\models\catalog\CatalogFeature;
use common\models\catalog\CatalogProductFeature;
use common\models\catalog\CatalogProductGallery;
use common\models\catalog\CatalogProductGalleryValue;
use common\models\catalog\CatalogProductTierPrice;
use common\models\catalog\CatalogStoreProduct;
use Yii;
use backend\components\CurrentUser;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogAttributeSet;
use common\models\catalog\CatalogAttributeType;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogProductAttributeSet;
use common\models\core\Store;
use common\components\CurrentStore;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\UploadForm;
use yii\web\UploadedFile;
use yii\caching\TagDependency;
use yii\helpers\VarDumper;
use common\models\catalog\CatalogBrandStore;


class ImportController extends Controller
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
                    // ...
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }
        $store         = CurrentStore::getStore();
        $upload        = new UploadForm();
        $attributeSets = CatalogAttributeSet::findAll(['is_active' => true]);

        $attributeSets = CatalogAttributeSet::find()
            ->where(['is_active' => true])
            ->andWhere(['IN', 'id', CatalogProductAttributeSet::find()
                ->select('set_id')
                ->where(['store_id' => ['0', CurrentStore::getStoreId()]])
            ])
            ->all();

        if (Yii::$app->request->isPost) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file   = $upload->uploadCSV();

                if ($file) {
                    $result = self::importProducts(file_get_contents($file));
                } else {
                    $result = ['status' => 'Nothing to import: please check the specified file for errors.'];
                }
            } else {
                $result = ['status' => 'Nothing to import: specified file could not be uploaded.'];
            }
        }

        if (empty($store)) {
            $store = 'All';
        } else {
            $store = $store->name;
        }

        //Clear Product Cache
        $key   = 'products_'.CurrentStore::getStoreId();
        TagDependency::invalidate(Yii::$app->cache, $key);

        return $this->render('index', [
            'store'         => $store,
            'upload'        => $upload,
            'attributeSets' => $attributeSets,
            'result'        => isset($result) ? $result : null,
        ]);
    }

    public function actionPricing()
    {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }

        $store         = CurrentStore::getStore();
        $upload        = new UploadForm();


        $attributeSets = CatalogAttributeSet::find()
            ->where(['is_active' => true])
            ->andWhere(['IN', 'id', CatalogProductAttributeSet::find()
                ->select('set_id')
                ->where(['store_id' => ['0', CurrentStore::getStoreId()]])
            ])
            ->all();

        if (Yii::$app->request->isPost) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file   = $upload->uploadCSV();

                if ($file) {
                    $result = self::importPricing(file_get_contents($file));
                } else {
                    $result = ['status' => 'Nothing to import: please check the specified file for errors.'];
                }
            } else {
                $result = ['status' => 'Nothing to import: specified file could not be uploaded.'];
            }
        }

        if (empty($store)) {
            $store = 'All';
        } else {
            $store = $store->name;
        }

        //Clear Product Cache
        $cache = Yii::$app->cache;
        $key   = 'category_products_'.CurrentStore::getStoreId();
        $cache->delete($key);

        return $this->render('pricing', [
            'store'         => $store,
            'upload'        => $upload,
            'attributeSets' => $attributeSets,
            'result'        => isset($result) ? $result : null,
        ]);
    }

    public function actionGroup()
    {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }

        $store         = CurrentStore::getStore();
        $upload        = new UploadForm();

        $attributeSets = CatalogAttributeSet::find()
            ->where(['is_active' => true])
            ->andWhere(['IN', 'id', CatalogProductAttributeSet::find()
                ->select('set_id')
                ->where(['store_id' => ['0', CurrentStore::getStoreId()]])
            ])
            ->all();

        if (Yii::$app->request->isPost) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file   = $upload->uploadCSV();

                if ($file) {
                    $result = self::importGroupPricing(file_get_contents($file));
                } else {
                    $result = ['status' => 'Nothing to import: please check the specified file for errors.'];
                }
            } else {
                $result = ['status' => 'Nothing to import: specified file could not be uploaded.'];
            }
        }

        if (empty($store)) {
            $store = 'All';
        } else {
            $store = $store->name;
        }

        //Clear Product Cache
        $cache = Yii::$app->cache;
        $key   = 'category_products_'.CurrentStore::getStoreId();
        $cache->delete($key);

        return $this->render('group', [
            'store'         => $store,
            'upload'        => $upload,
            'attributeSets' => $attributeSets,
            'result'        => isset($result) ? $result : null,
        ]);
    }

    public function actionGroupTieredPricing()
    {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }
        $store         = CurrentStore::getStore();
        $upload        = new UploadForm();

        $attributeSets = CatalogAttributeSet::find()
            ->where(['is_active' => true])
            ->andWhere(['IN', 'id', CatalogProductAttributeSet::find()
                ->select('set_id')
                ->where(['store_id' => ['0', CurrentStore::getStoreId()]])
            ])
            ->all();

        if (Yii::$app->request->isPost) {
            $upload->file = UploadedFile::getInstance($upload, 'file');

            if ($upload->file) {
                $file   = $upload->uploadCSV();

                if ($file) {
                    $result = self::importTierPricing(file_get_contents($file));
                } else {
                    $result = ['status' => 'Nothing to import: please check the specified file for errors.'];
                }
            } else {
                $result = ['status' => 'Nothing to import: specified file could not be uploaded.'];
            }
        }

        if (empty($store)) {
            $store = 'All';
        } else {
            $store = $store->name;
        }

        //Clear Product Cache
        $cache = Yii::$app->cache;
        $key   = 'category_products_'.CurrentStore::getStoreId();
        $cache->delete($key);

        return $this->render('group-tiered', [
            'store'         => $store,
            'upload'        => $upload,
            'attributeSets' => $attributeSets,
            'result'        => isset($result) ? $result : null,
        ]);
    }
    public function actionTemplate() {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }

        if (CurrentUser::isAdmin()) {
            $cols = ['store', 'sku', 'brand', 'url-key', 'type', 'category', 'attribute-set', 'features'];
            foreach (CatalogAttribute::findAll(['is_active' => true]) as $attribute) {
                $cols[] = $attribute->slug;
            } // Fetch all available attributes to populate remaining columns
        } else {
            $cols = ['brand', 'url-key', 'type', 'category', 'attribute-set', 'features', 'name'];
            foreach (CatalogAttribute::findAll(['is_active' => true, 'is_editable' => true]) as $attribute) {
                $cols[] = $attribute->slug;
            } // Fetch all available attributes to populate remaining columns
        }

        foreach ($cols as $index => $col) {
            if (!$index) {
                $csv  = $col;
            } else {
                $csv .= ",$col";
            }
        }

        return Yii::$app->response->sendContentAsFile($csv, 'import-template.csv', ['mimeType' => 'text/csv']);
    }

    public function actionExport($type = false) {
        if (CurrentUser::getUserId() == 12){
            throw new \yii\web\HttpException(404, 'You do not have permission to view the requested page.');
        }
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 3000);
        $baseColumns      = ['store', 'brand','url-key', 'type', 'attribute-set', 'parent-sku'];
        $excludeColumns   = ['short-description', 'description', 'meta-title', 'meta-description'];
        $attributes       = CatalogAttribute::find()
                            ->where(['is_active' => true])
                            ->andWhere(['not in','slug',$excludeColumns])
                            ->orderby(['export_sort'=>SORT_DESC])->all();

        $attributeColumns = ArrayHelper::getColumn($attributes, 'slug');
        $columns    = array_merge($baseColumns, $attributeColumns);
        $output     = '';
        $storeId    = CurrentStore::isNone() ? Store::NO_STORE : CurrentStore::getStoreId();
        $storeName  = CurrentStore::getStoreId() ? CurrentStore::getStore()->url : 'admin';
        $connection = Yii::$app->getDb();
        $brand = '';
        $type = '';
        $attribute_set = '';
        $attribute_set_where = '';
        $category = '';
        $category_where = '';

        if($data = Yii::$app->request->post()) {
            if (isset($data['brand_id']) && !empty($data['brand_id']) && $data['brand_id'][0] != NULL) {
                $brand = " and brand_id in (" . implode(',', $data['brand_id']) . ")";
            }

            if (isset($data['type_id']) && !empty($data['type_id'])) {
                $type = " and type = '" . $data['type_id'] . "'";
            }
            if (isset($data['attribute_id']) && !empty($data['attribute_id'])) {
                $attribute_set = " inner join catalog_product_attribute_set on catalog_product.id =  catalog_product_attribute_set.product_id";
                $attribute_set_where = " and set_id = '" . $data['attribute_id'] . "'";
            }

            if (isset($data['category_id']) && !empty($data['category_id']) && $data['category_id'][0] != NULL) {
                $category = " inner join catalog_category_product on catalog_product.id =  catalog_category_product.product_id";
                $category_where = " and category_id in (" . implode(',', $data['category_id']) . ")";
            }

            $sql = "SELECT catalog_product.* FROM catalog_product " . $attribute_set . $category . " 
                            WHERE  catalog_product.store_id = $storeId " . $brand . $type . $attribute_set_where . $category_where;

            $command = $connection->createCommand($sql);
            $products = $command->queryAll();


            foreach ($products as $index => $product) {
                $row = [];
                $row[] = CurrentStore::getStoreId() ? CurrentStore::getStore()->url : 'admin';
                $row[] = CatalogProduct::getBrandSlug($product['id']);
                $row[] = '"'.$product['slug'].'"';
                $row[] = $product['type'];
               // $row[] = addslashes((CatalogProduct::getCategoriesString($product['id'])) ? CatalogProduct::getCategoriesString($product['id']) : '');
                $row[] = CatalogProduct::getAttributeSet($product['id']);
                $row[] = CatalogProduct::getParentSku($product['id']);


                foreach ($attributeColumns as $column) {
                    $cell = CatalogProduct::getAttributeValue($product['id'], $column);

                    if (!empty($cell)) {
                        $attributeType = CatalogAttribute::findOne(['slug' => $column])->type_id;
                        $typeSelect = CatalogAttributeType::findOne(['format' => 'select'])->id;

                        if ($attributeType == $typeSelect)
                            $attributeOption = CatalogAttributeOption::findOne(['id' => $cell]);
                        if (!empty($attributeOption->value) && isset($attributeOption->value)) {
                            $cell = $attributeOption->value;
                        }
                    }

                    $cell = '"' . htmlentities($cell) . '"';
                    $row[] = $cell;

                }
                $output .= "\r\n" . implode(',', $row);
            }

            $output = implode(',', $columns) . $output;
            return Yii::$app->response->sendContentAsFile($output, "products-$storeName-export.csv", ['mimeType' => 'text/csv']);


            //return "Export failed: Invalid type or no products available for export...";
        }
    }

    public function adminimportProducts($file){

       $file = preg_split("/\\r\\n|\\r|\\n/", $file);

        if( empty($file) )
            die('File cannot be empty.');

        $timestamp = time();
        $results   = [
            'created'   => [
                'count' => 0,
                'messages' => []
            ],
            'updated'   => [
                'count' => 0,
                'messages' => []
            ],
            'failed'    => [
                'count' => 0,
                'messages' => []
            ],
            'activated' => [
                'count' => 0,
                'messages' => []
            ],
            'status' => ''
        ];
        $non_configurable  = ['price', 'special-price', 'special-price-starts', 'special-price-ends'];
        $base_columns      = ['store', 'brand', 'parent-sku', 'url-key', 'type', 'category', 'attribute-set', 'features'];
        $attribute_columns = ArrayHelper::getColumn(
            CatalogAttribute::findAll([
                'is_active' => true
            ]), 'slug'
        );
        $attributes = array_merge($base_columns, $attribute_columns);

        // Determine which $file $headers are supported $attributes
        $headers = str_getcsv($file[0]);
        foreach( $headers as $column_number => $header ){
            if( in_array(trim($header), $attributes) )
                $supported_attributes[] = trim($header);
        }
        if( !isset($supported_attributes)
            || !count($supported_attributes) )
            die('No supported columns found.');

        // Delete header row
        unset($file[0]);


        // Process rows
        $process_order = [
            'configurable',
            'grouped',
            'child-simple',
            'simple'
        ];
        foreach( $process_order as $process_order_type ){
            foreach( $file as $line_number => $row ){

                if( empty($row) ){
//                    $results['failed']['count'] += 1;
//                    $results['failed']['messages'][] = "Error at line $line_number: row is empty.";
                    continue;
                }
                $line = str_getcsv($row);

                // Determine store ID for this line
                if( array_search('store', $headers) === false ){
                    $results['failed']['count'] += 1;
                    $results['failed']['messages'][] = "Error at line $line_number: you must specify a store.";
                    continue;
                }
                $store = Store::findOne(['url' => $line[array_search('store', $headers)]]);
                $store = $store ? $store->id : 0;

                // Find product by SKU and store
                $sku     = $line[array_search('sku', $headers)];
                $product = CatalogProduct::findBySku($sku, $store);

                // Is this a new product? If so, use the $type
                // defined in $file. Else use the existing value.
                $is_new  = $product ? false : true;
                $type    = $is_new  ? strtolower(trim($line[array_search('type', $headers)]))
                    : $product->type;


                // Begin processing the product if it's
                // of the correct $type by $process_order
                if( $type !== $process_order_type )
                    continue;

                // Find parent by SKU and store
                $parent    = $process_order_type == 'child-simple' ?
                    $line[array_search('parent-sku', $headers)] : false;
                $parent    = $parent ? CatalogProduct::findBySku($parent, $store) : false;
                $parent_id = $parent ? $parent->id : '';

                $brand    = strtolower(trim($line[array_search('brand', $headers)]));
                $brand    = $brand ? CatalogBrand::findOne(['slug' => $brand]) : '';
                $brand_id = $brand ? $brand->id : '';

                $features = explode(',', $line[array_search('features', $headers)]);

                // Create or begin updating the project record
                if( $is_new ){
                    if( array_search('url-key', $headers) !== false
                        && $line[array_search('url-key', $headers)] !== '' ){
                        $slug = strtolower(trim($line[array_search('url-key', $headers)]));
                    } else {
                        // Generate a url-key if none is set
                        $name = str_replace(' ', '-',
                            strtolower(trim($line[array_search('name', $headers)])));
                        $comfort = str_replace(' ', '-',
                            strtolower(trim($line[array_search('mattress-comfort-level', $headers)])));
                        $size = str_replace(' ', '-',
                            strtolower(trim($line[array_search('mattress-size', $headers)])));
                        $slug = $brand ? "$brand->slug-" : '';

                        if( isset($name) )
                            $slug .= $name;
                        if( isset($comfort) )
                            $slug .= $comfort;
                        if( isset($size) )
                            $slug .= $size;
                    }
                    // Verify that this is a unique slug
                    // If it isn't, add a short semi-random hash
                    if( CatalogProduct::findOne(['slug' => $slug]) ){
                        $slug = "$slug-".hash('crc32', time().rand(0, 999));
                    }
                    $slug = ltrim(rtrim($slug, '-'), '-');

                    $product             = new CatalogProduct();
                    $product->store_id   = $store;
                    $product->brand_id   = $brand_id;
                    $product->parent_id  = $parent_id;
                    $product->slug       = $slug;
                    $product->type       = $type;
                    $product->created_at = $timestamp;
                } else {
                    $product->parent_id   = $parent_id;
                    $product->brand_id    = $brand_id;
                    $product->type        = $type;
                    $product->modified_at = $timestamp;
                }

                if( $product->save() ){
                    if( $is_new ){
                        $results['created']['count'] += 1;
                        $results['created']['messages'][] = "Created $sku at line $line_number.";
                    }

                    if( $features ){
                        foreach( $features as $feature ){
                            // Adding this lookup here for now
                            // to migrate ids from magento to wot_cart
                            if( $feature ){
                                $optionLookup = CatalogFeature::find()
                                    ->where([
                                        'option_id'=>$feature
                                    ])
                                    ->one();

                                if( $optionLookup ){
                                    $productFeature             = new CatalogProductFeature();
                                    $productFeature->feature_id = $optionLookup->id;
                                    $productFeature->product_id = $product->id;

                                    if ( !$productFeature->save() ) {
                                        $results['failed']['count'] += 1;
                                        $results['failed']['messages'][] = "Error at line $line_number: unable to associate $feature with $sku.";
                                    }
                                }
                            }
                        }
                    }

                    // Create or update this product's attributes
                    foreach( $line as $column_number => $cell ){
                        $current_column = strtolower(trim($headers[$column_number]));

                        if( in_array($current_column, $attribute_columns) ){
                            $cell = trim($cell);

                            if( !empty($cell)
                                || $cell == '0'
                                || $cell == '0.00' ){
                                $attribute    = CatalogAttribute::findOne(['slug' => $headers[$column_number]]);
                                $isApplicable = $type == 'configurable' ? in_array($attribute->slug, $non_configurable) ? false : true : true;

                                if( $attribute
                                    && $isApplicable ){
                                    switch ($attribute->type_id) {
                                        case CatalogAttributeType::BOOLEAN:
                                            $skip  = false;
                                            $value = $cell == 1 ? 1 : strtolower($cell) == 'yes' ? 1 : 0;

                                            if( $value
                                                && $type == 'child-simple'
                                                && $attribute->slug == 'active' ){
                                                if( !empty($parent) ){
                                                    CatalogAttributeValue::setValue(
                                                        CatalogAttribute::findOne([
                                                            'slug' => 'active'
                                                        ])->slug, '1', $parent->id, $store
                                                    );
                                                    $results['activated']['count'] += 1;
                                                    $results['activated']['messages'][] = "Activated $sku at line $line_number.";
                                                }
                                            }
                                            break;

                                        case CatalogAttributeType::SELECT:
                                            $skip  = false;
                                            $value = CatalogAttributeOption::findOne(['value' => $cell]);

                                            if( empty($value) ) {
                                                $results['failed']['count'] += 1;
                                                $results['failed']['messages'][] = "Error at line $line_number: unable to match $cell in $attribute->slug.";
                                                continue;
                                            } else {
                                                $value = $value->id;
                                            }
                                            break;

                                        case CatalogAttributeType::IMAGE:
                                            $skip  = false;
                                            $value = CatalogAttributeOption::findOne(['value' => $cell]);

                                            if( empty($value) ) {
                                                $results['failed']['count'] += 1;
                                                $results['failed']['messages'][] = "Error at line $line_number: unable to match $cell in $attribute->slug.";
                                                continue;
                                            } else {
                                                $value = trim($value->id);
                                            }
                                            break;

                                        case CatalogAttributeType::MULTISELECT:
                                            $skip   = true;
                                            $values = explode(',', str_replace(' ', '', $cell));

                                            foreach( $values as $value ){
                                                if( $value ){
                                                    if( $attribute->slug == 'compatible-brands' ){
                                                        $value = strtolower($value);
                                                        $compatible_brand = CatalogBrand::findOne(['slug' => $value]);

                                                        if( $compatible_brand ) {
                                                            $value = $compatible_brand;
                                                        } else {
                                                            $results['failed']['count'] += 1;
                                                            $results['failed']['messages'][] = "Error at line $line_number: unable to match $value in $attribute->slug.";
                                                            continue;
                                                        }
                                                    } else {
                                                        $value = CatalogAttributeOption::findOne(['value' => $value]);
                                                    }

                                                    if( !$is_new ) {
                                                        // Remove any entries created before
                                                        // the current import began
                                                        CatalogAttributeValue::deleteAll(
                                                            'attribute_id = :aid AND store_id = :sid AND product_id = :pid AND created_at < :tim',
                                                            [
                                                                ':aid' => $attribute->id,
                                                                ':sid' => $store,
                                                                ':pid' => $product->id,
                                                                ':tim' => $timestamp
                                                            ]
                                                        );
                                                    }
                                                    $attributeValue               = new CatalogAttributeValue();
                                                    $attributeValue->attribute_id = $attribute->id;
                                                    $attributeValue->store_id     = $store;
                                                    $attributeValue->product_id   = $product->id;
                                                    $attributeValue->value        = strval($value->id);
                                                    $attributeValue->created_at   = $timestamp;

                                                    if( !$attributeValue->save() ) {
                                                        $results['failed']['count'] += 1;
                                                        $results['failed']['messages'][] = "Error at line $line: unable to create value for $attribute->slug.";
                                                        continue;
                                                    }
                                                }
                                            }
                                            break;

                                        default:
                                            $skip  = false;
                                            $value = mb_convert_encoding($cell, 'UTF-8');
                                            break;
                                    }
                                    if (array_search('base-image', $headers) !== false && $line[array_search('base-image', $headers)] !== '') {
                                        $base_image =  $line[array_search('base-image', $headers)];

                                        CatalogProductGallery::deleteProductGalleryImages($product->id);

                                        $catalogProductGallery = new CatalogProductGallery();
                                        $catalogProductGallery->attribute_id = CatalogAttribute::getAttributeBySlug('base-image')->id;
                                        $catalogProductGallery->product_id   = $product->id;
                                        $catalogProductGallery->value        = $base_image;

                                        if($catalogProductGallery->save()){

                                            $catalogProductGalleryVal = new CatalogProductGalleryValue();
                                            $catalogProductGalleryVal->gallery_id  = $catalogProductGallery->id;
                                            $catalogProductGalleryVal->store_id    = $store;
                                            $catalogProductGalleryVal->is_active   = 0;
                                            $catalogProductGalleryVal->is_default  = 1;
                                            //VarDumper::dump($catalogProductGalleryVal, 4, 1); die;
                                            $catalogProductGalleryVal->save();
                                        }
                                    }

                                    if (array_search('media-gallery', $headers) !== false && $line[array_search('media-gallery', $headers)] !== '') {
                                        $gallery_images =  explode(',', $line[array_search('media-gallery', $headers)]);

                                        foreach ($gallery_images as $image){
                                            $catalogProductGallery = new CatalogProductGallery();
                                            $catalogProductGallery->attribute_id  = CatalogAttribute::getAttributeBySlug('media-gallery')->id;
                                            $catalogProductGallery->product_id    = $product->id;
                                            $catalogProductGallery->value         = $image;
                                            $catalogProductGalleryVal->store_id   = $store;
                                            $catalogProductGalleryVal->is_default = 0;
                                            $catalogProductGalleryVal->is_active  = 0;
                                            $catalogProductGalleryVal->is_deleted = 0;
                                            if($catalogProductGallery->save()){

                                            }
                                        }

                                    }
                                    if( $skip == false
                                        && isset($value) ){
                                        $value = strval($value);

                                        $current_value = CatalogAttributeValue::findOne([
                                            'attribute_id' => $attribute->id,
                                            'store_id'     => $store,
                                            'product_id'   => $product->id
                                        ]);

                                        // If no value exists for this attribute, or if the
                                        // incoming value is different from the current
                                        if( $current_value ){
                                            $current_value->value       = $value;
                                            $current_value->modified_at = $timestamp;
                                        } else {
                                            $current_value               = new CatalogAttributeValue();
                                            $current_value->attribute_id = $attribute->id;
                                            $current_value->store_id     = $store;
                                            $current_value->product_id   = $product->id;
                                            $current_value->value        = $value;
                                            $current_value->created_at   = $timestamp;
                                        }

                                        if( !$current_value->save() ){
                                            $results['failed']['count'] += 1;
                                            $results['failed']['messages'][] = "Error at line $line_number: unable to create value for $attribute->slug.";
                                            continue;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Create or update this product's category
                    if( array_search('category', $headers) !== false
                        && $line[array_search('category', $headers)] !== ''){
                        $category = CatalogCategory::findOne([
                            'slug' => strtolower($line[array_search('category', $headers)])
                        ]);

                        if( $category ){
                            if( !$is_new ){
                                $product_category = CatalogCategoryProduct::findOne([
                                    'product_id' => $product->id
                                ]);

                                if( $product_category ){
                                    $product_category->category_id = $category->id;
                                    $product_category->modified_at = $timestamp;
                                } else {
                                    // This is an update, but for some reason
                                    // the association does not yet exist
                                    $product_category              = new CatalogCategoryProduct();
                                    $product_category->category_id = $category->id;
                                    $product_category->product_id  = $product->id;
                                    $product_category->created_at  = $timestamp;
                                }
                            } else {
                                $product_category              = new CatalogCategoryProduct();
                                $product_category->category_id = $category->id;
                                $product_category->product_id  = $product->id;
                                $product_category->created_at  = $timestamp;
                            }

                            if( !$product_category->save() ){
                                $results['failed']['count'] += 1;
                                $results['failed']['messages'][] = "Error at line $line_number: unable to associate $sku with specified category.";
                                continue;
                            }
                        } else {
                            $results['failed']['count'] += 1;
                            $results['failed']['messages'][] = "Error at line $line_number: missing or invalid category.";
                            continue;
                        }
                    }

                    // Create or update this product's attribute set
                    if( array_search('attribute-set', $headers) !== false
                        && $line[array_search('attribute-set', $headers)] !== '' ){
                        $attribute_set = CatalogAttributeSet::findOne([
                            'slug' => strtolower($line[array_search('attribute-set', $headers)])
                        ]);

                        if( $attribute_set ){
                            if( !$is_new ){
                                $product_attribute_set = CatalogProductAttributeSet::findOne([
                                    'product_id' => $product->id,
                                    'store_id'   => $store
                                ]);

                                if( $product_attribute_set ){
                                    $product_attribute_set->set_id      = $attribute_set->id;
                                    $product_attribute_set->modified_at = $timestamp;
                                } else {
                                    // This is an update, but for some reason
                                    // the association does not yet exist
                                    $product_attribute_set             = new CatalogProductAttributeSet();
                                    $product_attribute_set->product_id = $product->id;
                                    $product_attribute_set->store_id   = $store;
                                    $product_attribute_set->set_id     = $attribute_set->id;
                                    $product_attribute_set->created_at = $timestamp;
                                }
                            } else {
                                $product_attribute_set             = new CatalogProductAttributeSet();
                                $product_attribute_set->product_id = $product->id;
                                $product_attribute_set->store_id   = $store;
                                $product_attribute_set->set_id     = $attribute_set->id;
                                $product_attribute_set->created_at = $timestamp;
                            }

                            if( !$product_attribute_set->save() ){
                                $results['failed']['count'] += 1;
                                $results['failed']['messages'][] = "Error at line $line_number: unable to associate $sku with specified attribute set.";
                                continue;
                            }
                        } else {
                            $results['failed']['count'] += 1;
                            $results['failed']['messages'][] = "Error at line $line_number: missing or invalid attribute set.";
                            continue;
                        }
                    }

                    // After all attributes and properties have
                    // been set, the record is considered updated
                    if( !$is_new ){
                        $results['updated']['count'] += 1;
                        $results['updated']['messages'][] = "Updated $sku at line $line_number.";
                    }
                } else {
                    $results['failed']['count'] += 1;
                    $results['failed']['messages'][] = "Error at line $line_number: unable to create new product record.";
                    continue;
                }
            }
        }
        $created   = $results['created']['count'];
        $updated   = $results['updated']['count'];
        $failed    = $results['failed']['count'];
        $activated = $results['activated']['count'];

        $results['status'] = "Import Completed: created $created products, updated $updated products, activated $activated products. Encountered $failed errors.";

        // Clear Product Cache
        $cache = Yii::$app->cache;
        $key   = 'category_products_mattresses_'.CurrentStore::getStoreId();
        $cache->delete($key);

        return $results;
    }
    public function importPricing($file){

        $stat             = [0, 0, 0, 0]; // [0] = new, [1] = updated, [2] = errors, [3] = activated
        $file = preg_split("/\\r\\n|\\r|\\n/", $file);

        // Determine which $file $headers are supported $columns
        $headers = str_getcsv($file[0]);

        foreach ($file as $lineNumber => $row) {
            if($lineNumber==0){ continue; }

            if ($row) {
                $line = str_getcsv($row);
                $mySKU   = trim($line[array_search('sku', $headers)]);
                $storeId = CurrentStore::getStoreId();

                if(!$storeId){
                    throw new \yii\web\HttpException(400,
                        'Pricing Import can only be ran as a individual store and not the admin store');
                }


                $product = CatalogProduct::findBySku($mySKU);



                if(!$storeId){
                    $results['status'] = "You must be assigned to a store to process this import. Use the context switcher to choose a store then try the import again.";

                }

                if(isset($product) && !empty($product)) {

                    $price = (isset($line[array_search('price', $headers)])) ? strtolower(trim($line[array_search('price', $headers)])) :'';
                    $special_price = (isset($line[array_search('special-price', $headers)])) ? strtolower(trim($line[array_search('special-price', $headers)])) :'';
                    $special_start = (isset($line[array_search('special-price-starts', $headers)])) ? strtolower(trim($line[array_search('special-price-starts', $headers)])) :'';
                    $special_end = (isset($line[array_search('special-price-ends', $headers)])) ? strtolower(trim($line[array_search('special-price-ends', $headers)])) :'';
                    $active = (isset($line[array_search('active', $headers)])) ? strtolower(trim($line[array_search('active', $headers)])) :'';

                    if(strtolower($active == 'yes')){ $active = 1; }
                    if(strtolower($active == 'no')){ $active = 0; }

                    if(empty($special_price) || !isset($special_price)){
                        $special_price = 0;
                    }


                    //Simple Product Attributes
                    CatalogAttributeValue::setValue('price', $price , $product->id, $storeId);
                    CatalogAttributeValue::setValue('special-price', $special_price, $product->id, $storeId);
                    CatalogAttributeValue::setValue('special-price-starts', date('Y-m-d', strtotime($special_start)), $product->id, $storeId);
                    CatalogAttributeValue::setValue('special-price-ends', date('Y-m-d', strtotime($special_end)), $product->id, $storeId);
                    CatalogAttributeValue::setValue('active', $active, $product->id, $storeId);

                    $stat[1] += 1;
                }else{
                    $stat[2] += 1;
                    $results['errors'][] = "Error on row $lineNumber: sku " . $mySKU . " could not be found";
                    continue;
                }

            }
        }

        //Clear Product Cache

        $key   = 'products-pricing-export-'.CurrentStore::getStoreId();


        $results['status'] = "Import Completed: added $stat[0] products, updated $stat[1] products, activated $stat[3] products. Encountered $stat[2] errors.";
        return $results;
    }
    public function importTierPricing($file){
        $stat = [0, 0, 0, 0]; // [0] = new, [1] = updated, [2] = errors, [3] = activated

        $file = preg_split("/\\r\\n|\\r|\\n/", $file);

        // Determine which $file $headers are supported $columns
        $headers = str_getcsv($file[0]);


        if($data = Yii::$app->request->post()) {
            $group = $data['UploadForm']['group_id'];
            $stores = Store::find()->where(['group_id'=>$group])->all();

            foreach ($stores as $store) {
                $connection = Yii::$app->getDb();
                $sql = "DELETE FROM catalog_product_tier_price WHERE  `store_id` = ". $store->id ." ";

                $command = $connection->createCommand($sql);
                $command->execute();

                foreach ($file as $lineNumber => $row) {
                    if ($lineNumber == 0) { continue; }

                    if ($row) {
                        $line = str_getcsv($row);
                        $mySKU = $line[array_search('sku', $headers)];
                        $storeId = $store->id;

                        if (!$storeId) {
                            throw new \yii\web\HttpException(400, 'Pricing Import can only be ran as a individual store and not the admin store');
                        }
                        $product = CatalogProduct::findBySku($mySKU);

                        if (isset($product) && !empty($product)) {

                            $sku               = $mySKU;
                            $quantity          = strtolower(trim($line[array_search('qty', $headers)]));
                            $price             = strtolower(trim($line[array_search('price', $headers)]));

                            $product            = CatalogProduct::findBySku($sku);
                            $tiered             = new CatalogProductTierPrice();
                            $tiered->store_id   = $storeId;
                            $tiered->product_id = $product->id;
                            $tiered->qty        = $quantity;
                            $tiered->value      = $price;
                            $tiered->sku        = $sku;
                            $tiered->save(false);

                            $stat[1] += 1;
                        } else {
                            $stat[2] += 1;
                            $results['errors'][] = "Error on row $lineNumber: sku " . $mySKU . " could not be found";
                            continue;
                        }

                    }
                }
            }
        }
        //Clear Product Cache

        $key   = 'products-pricing-export-'.CurrentStore::getStoreId();


        $results['status'] = "Import Completed: added $stat[0] products, updated $stat[1] products, activated $stat[3] products. Encountered $stat[2] errors.";
        return $results;
    }
    public function importGroupPricing($file){

        $stat = [0, 0, 0, 0]; // [0] = new, [1] = updated, [2] = errors, [3] = activated
        $file = preg_split("/\\r\\n|\\r|\\n/", $file);

        // Determine which $file $headers are supported $columns
        $headers = str_getcsv($file[0]);

        if($data = Yii::$app->request->post()) {
            $group = $data['UploadForm']['group_id'];
            $stores = Store::find()->where(['group_id'=>$group])->all();

            foreach ($stores as $store) {
                foreach ($file as $lineNumber => $row) {
                    if ($lineNumber == 0) {
                        continue;
                    }

                    if ($row) {
                        $line = str_getcsv($row);
                        $mySKU = $line[array_search('sku', $headers)];
                        $storeId = $store->id;

                        if (!$storeId) {
                            throw new \yii\web\HttpException(400, 'Pricing Import can only be ran as a individual store and not the admin store');
                        }


                        $product = CatalogProduct::findBySku($mySKU, $storeId);

                        if (isset($product) && !empty($product)) {

                            $price = (isset($line[array_search('price', $headers)])) ? strtolower(trim($line[array_search('price', $headers)])) :'';
                            $special_price = (isset($line[array_search('special-price', $headers)])) ? strtolower(trim($line[array_search('special-price', $headers)])) :'';
                            $special_start = (isset($line[array_search('special-price-starts', $headers)])) ? strtolower(trim($line[array_search('special-price-starts', $headers)])) :'';
                            $special_end = (isset($line[array_search('special-price-ends', $headers)])) ? strtolower(trim($line[array_search('special-price-ends', $headers)])) :'';
                            $active = (isset($line[array_search('active', $headers)])) ? strtolower(trim($line[array_search('active', $headers)])) :'';

                            if (strtolower($active == 'yes')) {
                                $active = 1;
                            }
                            if (strtolower($active == 'no')) {
                                $active = 0;
                            }

                            if (empty($special_price) || !isset($special_price)) {
                                $special_price = 0;
                            }

                            CatalogAttributeValue::setValue('price', $price, $product->id, $storeId);
                            CatalogAttributeValue::setValue('special-price', $special_price, $product->id, $storeId);
                            CatalogAttributeValue::setValue('active', $active, $product->id, $storeId);

                            //Set these if they only have date

                            if(isset($special_start) && !empty($special_start)){
                                CatalogAttributeValue::setValue('special-price-starts', date('Y-m-d', strtotime($special_start)), $product->id, $storeId);
                            }
                            if(isset($special_end) && !empty($special_end)){
                                CatalogAttributeValue::setValue('special-price-ends', date('Y-m-d', strtotime($special_end)), $product->id, $storeId);
                            }


                            $stat[1] += 1;
                        } else {
                            $stat[2] += 1;
                            $results['errors'][] = "Error on row $lineNumber: sku " . $mySKU . " could not be found";
                            continue;
                        }

                    }
                }
            }
        }

        $results['status'] = "Import Completed: added $stat[0] products, updated $stat[1] products, activated $stat[3] products. Encountered $stat[2] errors.";
        return $results;
    }
    public function actionPricingExport($type = 'pricing-products-export', $group=false) {
        $categories = '';
        if($data = Yii::$app->request->post()) {

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);
            $brand = null;


            if(isset($data['brand_id']) && !empty($data['brand_id']) && $data['brand_id'][0] != NULL){
                $brand = " and brand_id in (". implode(',',$data['brand_id']). ")";
            }
            if (isset($data['category']) && !empty($data['category'])) {
                if (!empty($data['category'])) {
                    $categories = $data['category'];
                }
            }

            $type = filter_var($type, FILTER_SANITIZE_STRING);
            $product_type = $type;


            $baseColumns = ['sku', 'brand', 'name'];
            $attributeColumns = ['price', 'special-price', 'special-price-starts', 'special-price-ends', 'active'];

            $columns = array_merge($baseColumns, $attributeColumns);
            $output = '';
            $storeId = CurrentStore::isNone() ? Store::NO_STORE : CurrentStore::getStoreId();
            $storeName = CurrentStore::getStoreId() ? CurrentStore::getStore()->url : 'admin';
            $connection = Yii::$app->getDb();

            if ($storeId) {

                if($categories){
                    $command = $connection->createCommand("
                    SELECT *
                    FROM   catalog_product
                    WHERE  `type` IN ('simple', 'child-simple')
                    $brand
                    AND  id IN (
                        SELECT product_id 
                        FROM catalog_category_product 
                        WHERE category_id IN ($categories));");
                }else{
                    $command = $connection->createCommand("
                    SELECT *
                    FROM   catalog_product
                    WHERE  `type` IN ('simple', 'child-simple')
                    $brand");
                }

            }else{
                return "Export failed: Pricing export must be generated from an individual store account";
            }

            $products = $command->queryAll();

            foreach ($products as $index => $product) {
                if (CatalogProduct::getAttributeValue($product['id'], 'sku', false, true) == null) {
                    continue;
                }
                $row = [];
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'sku', false, true);
                $row[] = CatalogBrand::getSlug($product['brand_id'], true);
                $row[] = '"'.CatalogProduct::getAttributeValue($product['id'], 'name', false, true).'"';
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'price', false, true);
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'special-price', false, true);
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'special-price-starts', false, true);
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'special-price-ends', false, true);
                $row[] = CatalogProduct::getAttributeValue($product['id'], 'active', false, true);


                $output .= "\r\n" . implode(',', $row);
            }

            $output = implode(',', $columns) . $output;
            return Yii::$app->response->sendContentAsFile($output, "$product_type-$storeName-export.csv", ['mimeType' => 'text/csv']);


            return "Export failed: Invalid type or no products available for export...";
        }
    }

    public function actionTieredPricingExport($type = 'pricing-products-export', $group=false) {
        if($data = Yii::$app->request->post()) {

            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);
            $brand = null;

            if(isset($data['group_id']) && !empty($data['group_id']) && $data['group_id'][0] != NULL){
                $group = $data['group_id'];
            }else{
                return "Export failed: No Group Selected.";
            }

            $columns = ['id', 'group', 'store', 'sku', 'qty', 'price'];
            $output = '';


            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
            SELECT `id`
            FROM `store`
            WHERE `legacy_store` = '" . $group . "' ");


            $store_ids = ArrayHelper::getColumn($command->queryAll(), 'id');

            foreach ($store_ids as $id) {
                $tiered_pricing =  CatalogProductTierPrice::find()->where(['store_id'=>$id])->orderby(['store_id'=>SORT_ASC, 'sku'=>SORT_DESC])->all();
                if($tiered_pricing){
                    foreach ($tiered_pricing as $tiered) {
                        if(isset($tiered)) {
                            $row = [];
                            $row[] = $tiered->id;
                            $row[] = $group;
                            $row[] = Store::getStoreById($id)->name;
                            $row[] = CatalogProduct::getSku($tiered->product_id);
                            $row[] = $tiered->qty;
                            $row[] = $tiered->value;
                            $output .= "\r\n" . implode(',', $row);
                        }
                    }
                }
            }

            $output = implode(',', $columns) . $output;
            return Yii::$app->response->sendContentAsFile($output, "tiered-pricing-$group-export.csv", ['mimeType' => 'text/csv']);


            return "Export failed: Invalid type or no tiered pricing available for export...";
        }
    }
    public function importProducts($file) {

        $file = preg_split("/\\r\\n|\\r|\\n/", $file);

        $lineCount = sizeof($file);
        //echo $lineCount; die;
        if (!empty($file)) {
            $isUpdate         = false;
            $time             = time();    // Group touched records by timestamp
            $stat             = [0, 0, 0, 0]; // [0] = new, [1] = updated, [2] = errors, [3] = activated
            $order            = ['configurable', 'grouped', 'child-simple', 'simple']; // Process products in this order
            $simpleAttributes = ['price', 'special-price', 'special-price-starts', 'special-price-ends'];

            if (CurrentUser::isAdmin()) {
                $baseColumns      = ['store', 'website', 'brand', 'url-key', 'product-id', 'type', 'category', 'attribute-set', 'features'];
                $attributes       = CatalogAttribute::findAll(['is_active' => true]);
                $attributeColumns = ArrayHelper::getColumn($attributes, 'slug');
            }
            $columns = array_merge($baseColumns, $attributeColumns);

            // Determine which $file $headers are supported $columns
            $headers = str_getcsv($file[0]);

            foreach ($headers as $columnNumber => $header) {
                if (in_array(trim($header), $columns))
                    $supported[] = trim($header);
            }


            if (isset($supported) && count($supported)) {
                foreach ($order as $type) {
                    foreach ($file as $lineNumber => $row) {
                        if ($lineNumber && $row) {
                            $line    = str_getcsv($row);

                            $mySKU = '';
                            if(array_search('sku', $headers)) {
                                $mySKU = (isset($line[array_search('sku', $headers)]) && !empty($line[array_search('sku', $headers)])) ? $line[array_search('sku', $headers)] : null;

                            }
                            if(empty($mySKU)){
                                continue;
                            }

                            $product = CatalogProduct::findBySku($mySKU);

                            $isNew   = empty($product) ? true : false;

                            $isUpdate    = ($isNew) ? false : true;

                            if (!$isNew) {
                                $lineType = array_search('type', $headers) !== false ? strtolower(trim($line[array_search('type', $headers)])) : $product->type;
                            } else {
                                $lineType = strtolower(trim($line[array_search('type', $headers)]));
                            }


                            if ($lineType == $type) {
                                $isAdmin     = CurrentUser::isAdmin();
                                $myBrand     = strtolower($line[array_search('brand', $headers)]);
                                $myBrand     = $myBrand == null ? $myBrand : CatalogBrand::findOne(['slug' => $myBrand]);
                                $myBrandId   = $myBrand == null ? $myBrand : $myBrand->id;

                                // Find parent by SKU and store
                                $parent    = ($lineType == 'child-simple') ? $line[array_search('parent-sku', $headers)] : false;
                                $parent    = $parent ? CatalogProduct::findBySku($parent, 0) : false;
                                $parent_id = $parent ? $parent->id : '';

                                $stores      = array_search('store', $headers) !== false ? $line[array_search('store', $headers)] : [CurrentStore::getStore()->url];
                                $stores      = $isAdmin ? !is_array($stores) ? explode(',', $stores) : $stores : [CurrentStore::getStore()->url];



                                foreach ($stores as $store) {
                                    $store = strtolower(trim($store));
                                    $store = $store == 'admin' || $store == 'all' ? $store : Store::findOne(['url' => $store]);

                                    if ($store) {
                                        $storeId     = $store == 'admin' || $store == 'all' ? '0' : $store->id;
                                        $isOverwrite = $isNew ? false : $product->store_id == $storeId ? true : false;

                                        // Only admins can create products via import
                                        if ($isNew && $isAdmin) {
                                            if (array_search('url-key', $headers) !== false && $line[array_search('url-key', $headers)] !== '') {
                                                $slug    = strtolower(trim($line[array_search('url-key', $headers)]));
                                            } else {
                                                // If no url-key is set, generate one
                                                $name    = str_replace(' ', '-', strtolower(trim($line[array_search('name', $headers)])));
                                                $slug    = $myBrand ? "$myBrand->slug-" : '';

                                                if (isset($name))
                                                    $slug .= $name;
                                            }

                                            // Verify that this is a unique slug
                                            // If it isn't, add a short semi-random hash
                                            if (CatalogProduct::findOne(['slug' => $slug])) {
                                                $slug = "$slug-".hash('crc32', time() . rand(0, 999));
                                            }

                                            $product             = new CatalogProduct();
                                            $product->store_id   = $storeId;
                                            $product->brand_id   = $myBrandId;
                                            $product->parent_id  = $parent_id;
                                            $product->slug       = ltrim(rtrim($slug, '-'), '-');
                                            $product->type       = $lineType;
                                            $product->created_at = $time;

                                        } elseif ($isOverwrite) {
                                            if ($isAdmin) {
                                                $product->store_id  = $storeId;
                                                $product->parent_id = $parent_id;
                                            }
                                            if ($myBrandId)
                                                $product->brand_id    = $myBrandId;

                                            $product->type        = $lineType;
                                            $product->modified_at = $time;
                                        }

                                        if($line[array_search('legacy-category', $headers)]){
                                            $product->legacy_category_ids = trim($line[array_search('legacy-category', $headers)]);

                                            if (isset($product->legacy_category_ids)) {

                                                $legacy_category_ids = explode(',', $product->legacy_category_ids);


                                                CatalogCategoryProduct::deleteAll([
                                                    'product_id' => $product->id,
                                                ]);
                                                foreach ($legacy_category_ids as $id) {
                                                    $mageCategorySlug = CatalogCategoryMagento::find()->where([
                                                        'category_id' => $id
                                                    ])->one();

                                                    if ($mageCategorySlug) {
                                                        $wotCategory = CatalogCategory::find()->where([
                                                            'slug' => $mageCategorySlug->slug
                                                        ])->one();


                                                        if ($wotCategory && $product->id) {

                                                            $category = new CatalogCategoryProduct();
                                                            $category->category_id = $wotCategory->id;
                                                            $category->product_id = $product->id;
                                                            $category->created_at = $time;
                                                            $category->is_active = true;
                                                            $category->is_deleted = false;
                                                            $category->save(false);
                                                        }

                                                    }
                                                }


                                            }
                                        }
                                        if ($product->save() || $isUpdate) {
                                            $stat[0] += $isUpdate ? 0 : 1;
                                            $stat[1] += $isUpdate ? 1 : 0;


                                            //Add website column to CatalogStoreProduct
                                            $websites      = array_search('website', $headers) !== false ? $line[array_search('website', $headers)] : null;
                                            $websites      = ($websites) ? explode(',', $websites) : '';

                                            //print_r($websites);
                                            if($websites){
                                                foreach($websites as $website) {
                                                    if (strtolower($website) == 'all') {
                                                        $web_stores = Store::find()->all();
                                                    } else {
                                                        $web_stores = Store::find()->where(['url' => $website])->all();
                                                    }
                                                }
                                                foreach($web_stores as $store){
                                                    $store_id = $store->id;
                                                    //if(CatalogStoreProduct::deleteAll('product_id = :product_id', [':product_id' => $product->id])){
                                                        $storeProduct  = new CatalogStoreProduct();
                                                        $storeProduct->store_id   = $store_id;
                                                        $storeProduct->product_id = $product->id;
                                                        if(!$storeProduct->save(false)){

                                                           $results['errors'][] = "Error on row $lineNumber: unable to associate store to product.";
                                                        }
                                                    //}
                                                }
                                            }

                                            foreach ($line as $columnNumber => $cell) {
                                                if (in_array(strtolower(trim($headers[$columnNumber])), $attributeColumns)) {
                                                    $cell = trim($cell);

                                                    if (!empty($cell) || $cell == '0' || $cell == '0.00') {
                                                        $attribute    = CatalogAttribute::findOne(['slug' => $headers[$columnNumber]]);
                                                        $isEditable   = ($isAdmin || $attribute->store_id == CurrentStore::getStoreId()) ? true : $attribute->is_editable;
                                                        $isApplicable = $type == 'configurable' ? in_array($attribute->slug, $simpleAttributes) ? false : true : true;

                                                        if ($attribute && $isEditable && $isApplicable) {
                                                            switch ($attribute->type_id) {
                                                                case CatalogAttributeType::BOOLEAN:
                                                                    $skip  = false;
                                                                    $value = $cell == 1 ? 1 : strtolower($cell) == 'yes' ? 1 : 0;

                                                                    if ($value && $type == 'child-simple'
                                                                        && $attribute->slug == 'active') {
                                                                        $parent = CatalogProduct::findBySku($parent_id);
                                                                        if (!empty($parent)) {
                                                                            $stat[3] += 1;
                                                                            CatalogAttributeValue::setValue(CatalogAttribute::findOne(['slug' => 'active'])->slug, '1', $parent->id, $storeId);
                                                                        }
                                                                    }
                                                                    break;

                                                                case CatalogAttributeType::SELECT:
                                                                    $skip  = false;
                                                                    $value = CatalogAttributeOption::findOne(['value' => $cell]);

                                                                    if (empty($value)) {
                                                                        $stat[2] += 1;
                                                                        $results['errors'][] = "Error on row $lineNumber: unable to find a match for '$cell' in $attribute->slug";
                                                                        continue;
                                                                    } else {
                                                                        $value = $value->id;
                                                                    }
                                                                    break;

                                                                case CatalogAttributeType::MULTISELECT:
                                                                    $skip   = true;
                                                                    $values = explode(',', str_replace(' ', '', $cell));
                                                                    foreach ($values as $value) {
                                                                        if ($value) {
                                                                            if ($attribute->slug == 'compatible-brands') {
                                                                                $value        = strtolower($value);
                                                                                $catalogBrand = CatalogBrand::findOne(['slug' => $value]);

                                                                                if ($catalogBrand) {
                                                                                    $value = $catalogBrand;
                                                                                } else {
                                                                                    $stat[2] += 1;
                                                                                    $results['errors'][] = "Error on row $lineNumber: unable to find a match for '$value' in $attribute->slug";
                                                                                    continue;
                                                                                }
                                                                            } else {
                                                                                $value = CatalogAttributeOption::findOne(['value' => $value]);
                                                                            }

                                                                            if ($isUpdate) {
                                                                                // Remove any entries created before
                                                                                // the current import began
                                                                                CatalogAttributeValue::deleteAll(
                                                                                    'attribute_id = :aid AND store_id = :sid AND product_id = :pid AND created_at < :tim',
                                                                                    [
                                                                                        ':aid' => $attribute->id,
                                                                                        ':sid' => $storeId,
                                                                                        ':pid' => $product->id,
                                                                                        ':tim' => $time
                                                                                    ]
                                                                                );
                                                                            }
                                                                            $attributeValue               = new CatalogAttributeValue();
                                                                            $attributeValue->attribute_id = $attribute->id;
                                                                            $attributeValue->store_id     = $storeId;
                                                                            $attributeValue->product_id   = $product->id;
                                                                            $attributeValue->value        = strval($value->id);


                                                                            if (!$attributeValue->save()) {
                                                                                $stat[2] += 1;
                                                                                $results['errors'][] = "Error on row $lineNumber: unable to create a value for $attribute->slug";
                                                                                continue;
                                                                            }
                                                                        }
                                                                    }
                                                                    break;

                                                                default:
                                                                    $skip  = false;
                                                                    $value = mb_convert_encoding($cell, 'UTF-8');
                                                                    break;
                                                            }

                                                            if ($skip == false && isset($value)) {
                                                                $value = strval($value);

                                                                if ($isUpdate) {
                                                                    $attributeValue = CatalogAttributeValue::findOne([
                                                                        'attribute_id' => $attribute->id,
                                                                        'store_id'     => $storeId,
                                                                        'product_id'   => $product->id
                                                                    ]);

                                                                    // If no value exists for this attribute, or if the
                                                                    // incoming value is different from the current
                                                                    if (!isset($attributeValue) || $value != $attributeValue->value) {
                                                                        if ($attributeValue) {
                                                                            $attributeValue->value        = $value;
                                                                        } else {
                                                                            $attributeValue               = new CatalogAttributeValue();
                                                                            $attributeValue->attribute_id = $attribute->id;
                                                                            $attributeValue->store_id     = $storeId;
                                                                            $attributeValue->product_id   = $product->id;
                                                                            $attributeValue->value        = $value;
                                                                        }

                                                                        if (!$attributeValue->save()) {
                                                                            $stat[2] += 1;
                                                                            $results['errors'][] = "Error on row $lineNumber: unable to create a value for $attribute->slug";
                                                                            continue;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $attributeValue               = new CatalogAttributeValue();
                                                                    $attributeValue->attribute_id = $attribute->id;
                                                                    $attributeValue->store_id     = $storeId;
                                                                    $attributeValue->product_id   = $product->id;
                                                                    $attributeValue->value        = $value;

                                                                    if (!$attributeValue->save()) {
                                                                        $stat[2] += 1;
                                                                        $results['errors'][] = "Error on row $lineNumber: unable to create a value for $attribute->slug";
                                                                        continue;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            if (array_search('base-image', $headers) !== false && $line[array_search('base-image', $headers)] !== '') {
                                                $base_image =  $line[array_search('base-image', $headers)];
                                                CatalogProductGallery::deleteDefaultProductImage($product->id);

                                                $catalogProductGallery = new CatalogProductGallery();
                                                $catalogProductGallery->attribute_id = CatalogAttribute::getAttributeBySlug('base-image')->id;
                                                $catalogProductGallery->product_id   = $product->id;
                                                $catalogProductGallery->value        = $base_image;
                                                $catalogProductGallery->store_id    = $storeId;
                                                $catalogProductGallery->is_active   = 0;
                                                $catalogProductGallery->is_default  = 1;
                                                $catalogProductGallery->created_at  = time();
                                                $catalogProductGallery->save();

                                            }

                                            if (array_search('media-gallery', $headers) !== false && $line[array_search('media-gallery', $headers)] !== '') {
                                                $gallery_images =  explode(',', $line[array_search('media-gallery', $headers)]);
                                                CatalogProductGallery::deleteProductGalleryImages($product->id);
                                                foreach ($gallery_images as $image){
                                                    $catalogProductGallery = new CatalogProductGallery();
                                                    $catalogProductGallery->attribute_id = CatalogAttribute::getAttributeBySlug('media-gallery')->id;
                                                    $catalogProductGallery->product_id   = $product->id;
                                                    $catalogProductGallery->value        = $base_image;
                                                    $catalogProductGallery->store_id    = $storeId;
                                                    $catalogProductGallery->is_active   = 0;
                                                    $catalogProductGallery->is_default  = 0;
                                                    $catalogProductGallery->created_at  = time();

                                                    if($catalogProductGallery->save()){

                                                    }
                                                }

                                            }




                                            if ($isAdmin) {
                                                // Populate single product category
                                                if (array_search('category', $headers) !== false && $line[array_search('category', $headers)] !== '') {
                                                    $category = CatalogCategory::findOne([
                                                        'slug' => strtolower($line[array_search('category', $headers)])
                                                    ]);

                                                    if ($category) {
                                                        if ($isUpdate) {
                                                            $productCategory              = CatalogCategoryProduct::findOne([
                                                                'product_id' => $product->id
                                                            ]);
                                                            if ($productCategory) {
                                                                $productCategory->category_id = $category->id;
                                                                $productCategory->modified_at = $time;
                                                            } else {
                                                                // This is an update, but the association does not yet exist
                                                                $productCategory              = new CatalogCategoryProduct();
                                                                $productCategory->category_id = $category->id;
                                                                $productCategory->product_id  = $product->id;
                                                                $productCategory->created_at  = $time;
                                                            }
                                                        } else {
                                                            $productCategory              = new CatalogCategoryProduct();
                                                            $productCategory->category_id = $category->id;
                                                            $productCategory->product_id  = $product->id;
                                                            $productCategory->created_at  = $time;
                                                        }

                                                        if (!$productCategory->save(false)) {
                                                            $stat[2] += 1;
                                                            $results['errors'][] = "Error on row $lineNumber: unable to associate product with specified category";
                                                            continue;
                                                        }
                                                    } else {
                                                        $stat[2] += 1;
                                                        $results['errors'][] = "Error on row $lineNumber: unable to locate specified category'";
                                                        continue;
                                                    }
                                                }

                                                // Populate single product attribute set
                                                if (array_search('attribute-set', $headers) !== false && $line[array_search('attribute-set', $headers)] !== '') {
                                                    $attributeSet = CatalogAttributeSet::findOne([
                                                        'slug' => strtolower($line[array_search('attribute-set', $headers)])
                                                    ]);

                                                    if ($attributeSet) {

                                                        if ($isUpdate) {
                                                            $productAttributeSet = CatalogProductAttributeSet::findOne([
                                                                'product_id' => $product->id, 'store_id' => $storeId
                                                            ]);
                                                            if ($productAttributeSet) {
                                                                $productAttributeSet->set_id      = $attributeSet->id;
                                                                $productAttributeSet->modified_at = $time;
                                                            } else {
                                                                // This is an update, but the association does not yet exist
                                                                $productAttributeSet             = new CatalogProductAttributeSet();
                                                                $productAttributeSet->product_id = $product->id;
                                                                $productAttributeSet->store_id   = $storeId;
                                                                $productAttributeSet->set_id     = $attributeSet->id;
                                                                $productAttributeSet->created_at = $time;
                                                            }
                                                        } else {
                                                            $productAttributeSet             = new CatalogProductAttributeSet();
                                                            $productAttributeSet->product_id = $product->id;
                                                            $productAttributeSet->store_id   = $storeId;
                                                            $productAttributeSet->set_id     = $attributeSet->id;
                                                            $productAttributeSet->created_at = $time;
                                                        }

                                                        //print_r($productAttributeSet); die;
                                                        if (!$productAttributeSet->save(false)) {
                                                            $stat[2] += 1;
                                                            $results['errors'][] = "Error on row $lineNumber: unable to associate product with specified attribute set";
                                                            continue;
                                                        }
                                                    } else {
                                                        $stat[2] += 1;
                                                        $results['errors'][] = "Error on row $lineNumber: unable to locate specified attribute set'";
                                                        continue;
                                                    }
                                                }
                                            }
                                        } else {
                                            $stat[2] += 1;
                                            $results['errors'][] = "Error on row $lineNumber: unable to create new product record";
                                            continue;
                                        }
                                    } else {
                                        $stat[2] += 1;
                                        $results['errors'][] = "Error on row $lineNumber: invalid store '$store'";
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                }

                $results['status'] = "Import Completed: added $stat[0] products, updated $stat[1] products, activated $stat[3] products. Encountered $stat[2] errors.";

                //Clear Product Cache
                $cache = Yii::$app->cache;
                $key   = 'products_'.CurrentStore::getStoreId();
                $cache->delete($key);

                return $results;
            } else {
                return ['status' => 'Sorry, no supported columns were found'];
            }
        } else {
            return ['status' => 'Nothing to import'];
        }
    }

}