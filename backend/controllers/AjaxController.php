<?php

namespace backend\controllers;

use backend\models\CreateStoreWizard;
use common\models\catalog\CatalogBrand;
use common\models\core\Store;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AjaxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'brands' => ['GET'],
                    'validate-url' => ['POST'],
                ],
            ],
        ];
    }

    public function actionBrands(){
        $brands = CatalogBrand::find()->all();
        return Json::encode($brands);
    }

    public function actionValidateUrl(){
        $data = file_get_contents("php://input");
        $data = json_decode($data, TRUE);

        if( array_key_exists('test_url', $data) ){
            $test_url = $data['test_url'];

            $store = Store::find()->where([
                'url' => $test_url
            ])->one();

            if($store){
                return true;
            }
        }
        return false;
    }

    public function actionCreateStore(){
        $data = file_get_contents("php://input");
        $data = json_decode($data, TRUE);


        $insert_data = [
            'CreateStoreWizard' => [
                'name'  => $data['name'],
                'url'   => $data['url']
            ]
        ];
        if(array_key_exists('brands', $data)){
            $insert_data['CreateStoreWizard']['brands'] = $data['brands'];
        }

        $create_store_wizard = new CreateStoreWizard();
        $create_store_wizard->load( $insert_data );
        return $create_store_wizard->createStore();
    }
}