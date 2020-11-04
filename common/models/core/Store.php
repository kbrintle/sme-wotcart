<?php

namespace common\models\core;

use common\models\catalog\CatalogAttribute;
use common\models\catalog\CatalogBrandStore;
use common\models\settings\SettingsPayment;
use common\models\catalog\CatalogBrand;
use common\models\store\StoreLocation;
use Yii;
use common\components\CurrentStore;
use backend\components\CurrentUser;
use common\models\cms\CmsPage;
use common\models\core\AdminStore;
use common\models\core\query\StoreQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use common\models\core\StoreGroup;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $created_at
 * @property string $legacy_store
 * @property string $group_id
 * @property integer $updated_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $is_default
 */
class Store extends \yii\db\ActiveRecord
{
    const NO_STORE        = 0;
    const SME             = 1;
    const GOLD            = 0;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'created_at', 'is_active'], 'required'],
            [['created_at', 'updated_at', 'is_active', 'is_deleted', 'is_default', 'group_id'], 'integer'],
            [['name', 'url', 'legacy_store'], 'string', 'max' => 255],
        ];
    }

    public function getCmsPages(){
        return $this->hasMany(CmsPage::className(), ['id' => 'page_id'])
            ->viaTable('cms_page_store', ['store_id' => 'id']);
    }

    public function getBrands(){
        return $this->hasMany(CatalogBrandStore::className(), ['id' => 'brand_id'])
            ->viaTable('catalog_brand_store', ['store_id' => 'id'],
                function($query) {
                    $query->onCondition(['store_id' => CurrentStore::getStoreId()]);
                });
    }

    public function getGroup(){
        return $this->hasOne(StoreGroup::className(), ['id' => 'group_id']);

    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'is_default' => 'Is Default',
        ];
    }

    public function getCatalogAttributes(){
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
            SELECT `attribute_id`
            FROM `catalog_attribute__order`
            WHERE `store_id` = $this->id
            ORDER BY `order`
        ");
        $sort_ids = ArrayHelper::getColumn($command->queryAll(), 'attribute_id');

        if( count($sort_ids) ){
            return $this->hasMany(CatalogAttribute::className(), ['id' => 'attribute_id'])
                ->viaTable('catalog_attribute__order', ['store_id' => 'id'])
                ->orderBy([new \yii\db\Expression('FIELD (id, ' . implode(',', $sort_ids) . ')')]);
        }
        return [];
    }

    public static function getStore($activeOnly=1){
        return Store::find()
            ->where(['is_active' => Store::STATUS_ACTIVE])
            ->orderBy('name')
            ->all();
    }

    public static function getStoreById($id){
        return Store::find()
            ->where(['id' => $id])
            ->orderBy('name')
            ->one();
    }

    public static function getStoreBySlug($slug){
        $store = Store::find()
            ->where(['url' => $slug])
            ->one();

        return (isset($store)) ? $store : false;
    }

    public static function getStoreByLegacyCustomerGroup($name){
        $store = Store::find()
            ->where(['name' => $name])
            ->one();

        return (isset($store)) ? $store : false;
    }


    public function getStoreBrands(){
        return $this->hasMany(CatalogBrand::className(), ['id' => 'brand_id'])
            ->from(CatalogBrand::tableName())
            ->viaTable(CatalogBrandStore::tableName(), ['store_id' => 'id'],
                function($query) {
                    $query->onCondition(['store_id' => CurrentStore::getStoreId()]);
                });
    }



    public function getLocation(){
        return StoreLocation::normalFind()->where(['store_id' => $this->id])->one();
    }
    public function getStoreLocations(){
        return $this->hasMany(StoreLocation::className(), ['store_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return StoreQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreQuery(get_called_class());
    }

    public function active()
    {
        //return $this->andWhere(['is_active' => 1]);
    }

    public static function getStoreList($all = true)
    {
        $storesList = array();

        if (CurrentUser::isAdmin() && $all) {
            $storesList[0] = 'All';
            $stores = Store::find()
                ->where(['is_active' => Store::STATUS_ACTIVE, 'is_deleted' => false])
                ->orderBy('name')
                ->all();
        }else{
            $adminStores = AdminStore::find()->where(['admin_id' => CurrentUser::getUserId()])->indexBy('store_id')->all();
            $stores = Store::find()
                ->where(['is_active' => Store::STATUS_ACTIVE])
                ->where(['in','id', array_keys($adminStores)])
                ->orderBy('name')
                ->all();
        }
        foreach($stores as $store){
            $storesList[$store->id] = $store->name;
        }

        return $storesList;

    }

    public function getPaymentSettings(){
        return SettingsPayment::find()->where([
            'store_id' => [Store::NO_STORE, $this->id ]
        ])->orderBy([])
            ->one();
    }

}
