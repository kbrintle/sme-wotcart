<?php

namespace common\models\catalog;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "catalog_product".
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer $product_id
 * @property integer $store_id
 * @property string $value
 * @property integer $sort
 * @property integer $is_default
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_at
 */
class CatalogProductGallery extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'attribute_id', 'product_id', 'store_id', 'sort', 'is_default', 'is_active','is_deleted'], 'integer'],
            [['value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attribute_id' => 'Attribute ID',
            'product_id' => 'Product ID',
            'store_id' => 'Store ID',
            'value' => 'Value',
            'sort' => 'Sort',
            'is_default' => 'Is Default',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted'
        ];
    }

    public static function deleteProductGalleryImages($product_id = null, $store_id=0){
        if($product_id == null){
            return;
        }


        self::deleteAll([
            'product_id'=> $product_id,
            'store_id'  => $store_id,
            'is_default'=> false,
        ]);
    }

    public static function deleteDefaultProductImage($product_id = null, $store_id=0){
        self::deleteAll([
            'product_id'=> $product_id,
            'store_id'  => $store_id,
            'is_default'=> true,
        ]);

    }

    public static function getImages($product_id = null, $attribute='base-image', $store_id=0){
        if ($attribute=='base-image'){
            $image =  self::find()
                ->where([
                    'product_id'=> $product_id,
                    'store_id'  => $store_id,
                    'is_default'=> true,
                ])->one();

                return isset($image) ? $image->value : '';
        }else{
            $images = self::find()->where([
                'product_id'=> $product_id,
                'store_id'  => $store_id,
                'is_default'=> false,
            ])->orderby('sort')
                ->all();

            return ArrayHelper::getColumn($images, 'value');
        }

    }
}