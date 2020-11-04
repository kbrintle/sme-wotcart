<?php

namespace common\models\catalog;

use Yii;
use common\components\CurrentStore;
use common\models\catalog\query\CatalogBrandQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "catalog_brand".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $manufacturer_id
 * @property integer $mattress_line
 * @property string $name
 * @property string $slug
 * @property string $text
 * @property string $logo_color
 * @property string $logo_gre
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $is_editable
 * @property integer $sort_order
 * @property integer $created_at
 * @property integer $modified_at
 * @property CatalogProduct $catalogProduct
 * @property CatalogProduct[] $ids
 * @property CatalogProductFeature[] $ids0
 */
class CatalogBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_brand';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['is_active', 'store_id','is_deleted', 'manufacturer_id', 'is_editable', 'sort_order', 'created_at', 'modified_at'], 'integer'],
            [['sort_order'], 'required'],
            [['name', 'logo_color'], 'string', 'max' => 512],
            [['slug'], 'string', 'max' => 255],
            [['logo_color'], 'file', 'skipOnEmpty' => true, 'extensions' => 'svg, gif, png, jpg']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'text' => 'Text',
            'logo_color' => 'Color Logo',
            'masthead_image' => 'Brand Masthead',
            'is_active'   => 'Is Active',
            'is_deleted'  => 'Is Deleted',
            'is_editable' => 'Is Editable',
            'sort_order'  => 'Sort Order',
            'created_at'  => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function storeBrands() {
        return CatalogBrand::find()
            ->where(['IN', 'id', CatalogBrandStore::find()
                ->select('brand_id')
                ->where(['store_id' => CurrentStore::getStoreId()])
            ])
            ->all();
    }

    public static function getAvailableBrands() {
        if (CurrentStore::isNone()) {
            $brands = CatalogBrand::findAll([
                'is_active'  => true,
                'is_deleted' => false
            ]);
        } else {
            $brands = self::storeBrands();
        }
        return $brands;
    }

    public static function getSlug($brand_id, $empty = false)
    {
        if ($brand_id) {
            $brand = self::findOne($brand_id);

            if ($brand) {
                return $brand->slug;
            }
        }

        if( $empty )
            return '';

        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(CatalogProduct::className(), ['parent_id' => 'id'])->viaTable('catalog_product', ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds0()
    {
        return $this->hasMany(CatalogProductFeature::className(), ['id' => 'id'])->viaTable('catalog_product', ['id' => 'id']);
    }

    public function upload($current){

        // echo $this->logo_color; die;
        if( isset($this->logo_color) && !empty($this->logo_color) ){
            if( $this->logo_color != $current->logo_color ){
                $filename = '/brands/logos/' . $this->slug . '_color.' . $this->logo_color->extension;
                $location = Yii::getAlias("@frontend") . '/web/uploads';

                if( !empty($this->logo_color) && $this->logo_color->saveAs($location.$filename) ){
                    $this->logo_color = $filename;
                } else {
                    return false;
                }
            }
        }

        if( $this->save() ){
            return true;
        }

        return false;
    }

    public static function find()
    {
        return new CatalogBrandQuery(get_called_class());
    }
}
