<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "store_favorite_list_item".
 *
 * @property integer $item_id
 * @property integer $list_id
 * @property integer $product_id
 * @property integer $qty
 * @property string $description
 * @property string $sku
 * @property string $buy_request
 * @property integer $sort
 *
 * @property StoreFavoriteList $list
 */
class StoreFavoriteListItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_favorite_list_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list_id', 'product_id', 'qty', 'sort'], 'integer'],
            [['sku'], 'required'],
            [['buy_request'], 'string'],
            [['sku'], 'string', 'max' => 255],
            [['list_id'], 'exist', 'skipOnError' => true, 'targetClass' => StoreFavoriteList::className(), 'targetAttribute' => ['list_id' => 'list_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'list_id' => 'List ID',
            'product_id' => 'Product ID',
            'qty' => 'Qty',
            'sku' => 'sku',
            'buy_request' => 'Buy Request',
            'sort' => 'Sort',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getList()
    {
        return $this->hasOne(StoreFavoriteList::className(), ['list_id' => 'list_id']);
    }
}