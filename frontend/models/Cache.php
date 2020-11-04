<?php
namespace frontend\models;

use app\components\StoreUrl;
use common\models\catalog\CatalogAttributeOption;
use common\models\catalog\CatalogBrand;
use common\models\catalog\CatalogCategory;
use frontend\components\Assets;
use Yii;

use common\components\CurrentStore;
use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogAttributeValue;
use common\models\catalog\CatalogProduct;
use common\models\core\Store;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use common\models\catalog\CatalogProductGallery;

class Cache extends Model{

    public static function warmCache($store_id = null){

        if($store_id == null){
            $stores = Store::find()->where([
                'is_active'  => true,
                'is_deleted' => false,
            ])->all();
        }else{
            $stores = Store::find()->where([
                'id' => $store_id,
                'is_active'  => true,
                'is_deleted' => false,
            ])->all();
        }


        foreach ($stores as $store){
            // create curl resource
            $ch = curl_init();

            // set url
            curl_setopt($ch, CURLOPT_URL, "https://www.americasmattress.com/".$store->url."/shop/warm-cache/mattresses");

            $content = curl_exec( $ch );

            // close curl resource to free up system resources
            curl_close($ch);
        }
    }
}