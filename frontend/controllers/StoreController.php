<?php

namespace frontend\controllers;

use common\models\core\CoreUrlRewrite;
use common\models\store\StoreEvent;
use Yii;
use common\models\settings\SettingsStore;
use common\models\store\StoreBenefit;
use common\models\promotion\PromoImages;
use frontend\models\ZipLookupForm;
use common\models\settings\SettingsSeo;
use common\components\CurrentStore;
use yii\web\Controller;
use common\models\core\Store;
use yii\helpers\Json;
use common\models\catalog\CatalogProduct;
use common\models\catalog\CatalogBrandStore;
use common\models\catalog\CatalogCategoryProduct;
use common\models\catalog\CatalogCategory;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\models\store\StoreLocation;
use yii\helpers\VarDumper;
use yii\helpers\Url;
use app\components\StoreUrl;


class StoreController extends Controller
{
    public function actionRoute($request) {

        $login = false;
        $url = explode('/', ltrim($request->url, '/'));
        $key = false;


//        $allowedUrls = ['login', 'request-password-reset', 'request-password-reset-success', 'reset-password'];
//        if(isset($url[2]) && in_array($url[2], $allowedUrls) ){
//            $login = true;
//        }


        $url = explode('?', $url[0]);
        //echo $url; die;

        if (!CurrentStore::getStoreId())
            CurrentStore::setStore();

        if (($stores = Store::findAll(['is_deleted' => false]))) {
            foreach ($stores as $store) {
                // Try to find a store URL that matches
                // the request URL, storing the match as $key

                if (array_search($store->url, $url) !== false) {
                    $key = $store;
                    break; // Stop as soon as a match is found
                }
            }
        }

        if ($key === false) {
            // If no match was found (either because no store URL
            // was included in the request URL, or the request URL
            // references a store that does not exist) we need to:
            // 1) Inject the default store's URL into the request
            // 2) Potentially discard the bad store URL
            if (Yii::$app->createController($url[0]) === false) {
                // If the request value in the URL position is
                // not a controller, assume it's an invalid store
                // URL and discard it before completing the request
                unset($url[0]);
            }
            //echo CurrentStore::getStore()->url;
            $redirect_url = rtrim('/'.CurrentStore::getStore()->url.'/'.implode('/', $url), '/');
            Yii::$app->getResponse()->redirect([$redirect_url/*, $get_params*/], 301);
        } else {
            // Otherwise the request value in the URL position is
            // valid and the request can be completed, but first, since
            // URLs are "sticky", update the current store to the
            // store specified in the request
            CurrentStore::setStore($key->id);

//            if(!$store->is_default && Yii::$app->user->isGuest && !$login){
//                $to = 'account/login';
//                Yii::$app->response->redirect(StoreUrl::to($to), 301);
//
//            }

            $this->checkRedirects($request, $store->url);

            return true;
        }
    }

    public function checkRedirects($request, $store_url) {

        $url = $request->url;
        $segmented_url = explode('/', ltrim($url, '/'));

        if(isset($segmented_url[1]) && $segmented_url[1] =='search'){
            $url = '/'. $segmented_url[1].'?'.$segmented_url[2];
            Yii::$app->response->redirect(StoreUrl::to($url), 301);
            Yii::$app->end();
        }

        $url = explode($store_url, ltrim($request->url, '/'));

        if(isset($url[1]) && !empty($url[1])) {
            $action = $url[1];

            //Maybe its a product
            $product = CatalogProduct::find()->where(['slug' => str_replace('/', '', $action)])->one();

            if (strpos($action, '.html') !== false) {
                $action = str_replace('.html', '', $action);
                $product = CatalogProduct::find()->where(['slug' => str_replace('/', '', $action)])->one();
            }

            if (isset($product)) {
                $to = '/shop/products' . $action;
                Yii::$app->response->redirect(StoreUrl::to($to), 301);
                Yii::$app->end();
            }

            //echo $action;

            $rewrite = CoreUrlRewrite::find()->where(['request_path'=>$action])->one();


            if (isset($rewrite)) {
                Yii::$app->response->redirect(StoreUrl::to($rewrite->target_path), 301);
                Yii::$app->end();
            }
        }
        return true;
    }

    public function actionIndex($store) {

        $store = CurrentStore::getStore();
        if(!$store->is_default && Yii::$app->user->isGuest){
                $to = 'account/login';
                Yii::$app->response->redirect(StoreUrl::to($to), 301);
            }

        $settingsSeo = SettingsSeo::find()->one();
        if($settingsSeo){
            $this->view->title = (isset($settingsSeo->page_title) && !empty($settingsSeo->page_title)) ? $settingsSeo->page_title : 'Home';

            Yii::$app->view->registerMetaTag(
                [
                'name' => 'description',
                'content' => $settingsSeo->meta_description,
                ]
            );
            Yii::$app->view->registerMetaTag(
                [
                    'name' => 'keywords',
                    'content' => 'yii, framework, php'
                ]
            );
        }

        return $this->render('index', [
            'store'         => CurrentStore::getStore(),
            'promo_images'  => PromoImages::find()->homepage(),
            'events'        => StoreEvent::getEvents(6)
        ]);
    }

    public function actionFind(){
        if(Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();

            $zip_lookup = new ZipLookupForm();
            $zip_lookup->load( Yii::$app->request->post() );

            $store = $zip_lookup->findStoreByZip();
            if(!$store){
                $store = $zip_lookup->findStoresByZip();
            }

            $currentStoreSettings = CurrentStore::getStore()->name;

            $storeCode              = !is_array($store) ? $store->url : "";
            $storeUrl               = !is_array($store) ? "/$store->url" : "";
            $goingToStoreSettings   = !is_array($store) ? $store->name : "";

            $referrer = "";
            $referrer = explode(CurrentStore::getStore()->url, Yii::$app->request->referrer);

            if( isset($referrer[1]) ){
                $redirectUrl = $referrer[1];
            }


            $storeArr = array(
                'store'                 => $store,
                'redirectUrl'           => $redirectUrl,
                'currentStoreLocation'  => ucfirst($currentStoreSettings),
                'goingToStoreLocation'  => ucfirst($goingToStoreSettings),
                'storeCode'             => $storeCode,
                'storeUrl'              => $storeUrl,
                'currentStoreUrl'       => CurrentStore::getStore()->url,
                'zipCode'               => $zip_lookup->zip
            );

            echo Json::encode($storeArr);
        }

    }
    public function actionPolicy(){
        return $this->render('policy', [
            'settings'=>$settingsStore = SettingsStore::find()->one()
        ] );
    }
    
}