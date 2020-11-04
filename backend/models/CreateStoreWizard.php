<?php

namespace backend\models;

use common\models\catalog\CatalogBrand;
use common\models\core\Store;
use Yii;
use yii\base\Model;

class CreateStoreWizard extends Model{

    public $name;
    public $url;
    public $brands;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['name', 'url'], 'required'],
            [['name', 'url'], 'string'],
            [['brands'], 'safe']
        ];
    }

    public function createStore(){
        $store              = new Store();
        $store->name        = $this->name;
        $store->url         = $this->url;
        $store->created_at  = time();

        if($store->save()){
            $brands = CatalogBrand::find()->where([
                'id' => $this->brands
            ])->all();

            foreach($brands as $brand){
                $store->link('brands', $brand); // with message 'Getting unknown property: common\models\catalog\CatalogBrand::brand_id'
            }

            return true;
        }
        return false;
    }

}