<?php

namespace common\models\store;

use common\components\CurrentStore;
use Yii;
use yii\db\ActiveRecord;
use frontend\components\CurrentCustomer;
use common\models\store\StoreFavoriteListItem;
use common\models\catalog\CatalogProductOption;
use common\models\catalog\CatalogProduct;


/**
 * This is the model class for table "store_favorite_list".
 *
 * @property integer $list_id
 * @property string $title
 * @property integer $customer_id
 * @property string $created_at
 * @property integer $is_default
 * @property integer $is_deleted
 *
 * @property StoreFavoriteListItem[] $storeFavoriteListItems
 */
class StoreFavoriteList extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_favorite_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'is_default', 'is_deleted'], 'integer'],
            [['created_at', 'is_default'], 'required'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'list_id' => 'List ID',
            'title' => 'Title',
            'customer_id' => 'Customer ID',
            'created_at' => 'Created At',
            'is_default' => 'Is Default',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreFavoriteListItems()
    {
        return $this->hasMany(StoreFavoriteListItem::className(), ['list_id' => 'list_id']);
    }

    public function getStoreFavoriteListItemsCount($list_id)
    {
        return StoreFavoriteListItem::find()->where(["list_id" => $list_id])->sum("qty");
    }

    public function getCustomerFolders()
    {
        return StoreFavoriteList::find()->where([
            'customer_id' => CurrentCustomer::getCustomerId(), 'is_deleted' => false
        ])->all();
    }

    public function getFavoriteItems($list_id)
    {
        $items = [];
        $favoriteListItems = StoreFavoriteListItem::find()->where(['list_id' => $list_id])->orderBy('sort ASC')->all();
        foreach ($favoriteListItems as $favoriteListItem) {
            $product = CatalogProduct::findOne($favoriteListItem->product_id);
            if ($price = CatalogProduct::getPriceValue($favoriteListItem->product_id, false, 1, $favoriteListItem->sku)) {
                $price = $price['price'];
            }
            $options = CatalogProduct::getProductCustomOptions($favoriteListItem->product_id, $favoriteListItem->sku);
            $name = CatalogProduct::getName($favoriteListItem->product_id);
            $itemPrice = $price * $favoriteListItem->qty;
            $itemPrice = number_format($itemPrice, 2, '.', ',');

            if (isset($favoriteListItem->product_id) && isset($favoriteListItem->item_id) && isset($product->slug) && isset($favoriteListItem->qty) && isset($favoriteListItem->sku)) {
                $items[] = ['product_id' => $favoriteListItem->product_id, 'item_id' => $favoriteListItem->item_id, 'slug' => $product->slug, 'name' => $name, 'itemsPrice' => $itemPrice, 'options' => $options, 'qty' => $favoriteListItem->qty, 'sku' => $favoriteListItem->sku];
            }
        }
        return $items;
    }

}
