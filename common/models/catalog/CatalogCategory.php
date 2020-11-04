<?php

namespace common\models\catalog;

use common\components\CurrentStore;
use common\models\catalog\query\CatalogCategoryQuery;
use Yii;

/**
 * This is the model class for table "catalog_category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $store_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $image;
 * @property string $banner_image;
 * @property string $thumbnail;
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_homepage
 * @property integer $is_nav
 * @property integer $is_active
 * @property integer $is_deleted
 *
 * @property CatalogCategoryProduct[] $catalogCategoryProducts
 */
class CatalogCategory extends \yii\db\ActiveRecord
{

    public $url;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'store_id', 'created_at', 'modified_at', 'is_active', 'is_nav', 'is_homepage', 'is_deleted'], 'integer'],
            [['name', 'slug', 'created_at'], 'required'],
            [['name', 'slug', 'image', 'banner_image', 'thumbnail', 'description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'parent_id'     => 'Parent ID',
            'store_id'      => 'Store ID',
            'name'          => 'Name',
            'slug'          => 'Slug',
            'image'         => 'Image',
            'banner_image'  => 'Banner Image',
            'thumbnail'     => 'Thumbnail',
            'created_at'    => 'Created At',
            'modified_at'   => 'Modified At',
            'is_active'     => 'Is Active',
            'is_deleted'    => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogCategoryProducts()
    {
        return $this->hasMany(CatalogCategoryProduct::className(), ['category_id' => 'id']);
    }

    public static function find()
    {
        return new CatalogCategoryQuery(get_called_class());
    }

    public static function getAllCategories($limit = 10000, $children = false, $sort = SORT_ASC ,$store_id = false){

        $children = ($children) ? ['not', ['parent_id' => null]] : ['parent_id' => null];

        if(!$store_id){
            $store_id = CurrentStore::getStoreId();
        }

        return self::find()
            ->where(
                [
                    'is_active'  => true,
                    'is_deleted' => false
                ]
            )
            ->andWhere($children)
            ->andWhere(['or',
                ['store_id'=> 0],
                ['store_id'=> $store_id ]
            ])
            ->limit($limit)
            ->orderBy(['name'=>$sort])
            ->all();
    }
    public static function getHomePageCategories($limit = 10000, $sort = SORT_ASC){
        return self::find()
            ->where(
                [
                    'is_homepage'=> true,
                    'is_active'  => true,
                    'is_deleted' => false
                ]
            )
            ->andWhere(['or',
                ['store_id'=> 0],
                ['store_id'=> CurrentStore::getStore()]
            ])
            ->limit($limit)
            ->orderBy(['name'=>$sort])
            ->all();
    }

    public static function getCategory($slug){
        return self::find()->where(['slug'=>$slug])->one();
    }

    public static function getDescription($slug){
        return self::find()->where(['slug'=>$slug])->one()->description;
    }

    public static function getName($slug){
        return self::find()->where(['slug'=>$slug])->one()->name;
    }

    public static function getBanner($slug){
        $category = self::find()->where(['slug'=>$slug])->one();
        if(isset($category->parent_id) && $category->parent_id > 0 && $category->banner_image == null ){
            $parent_category = self::find()->where(['parent_id'=>$category->parent_id])->one();
            $banner_image =  $parent_category->banner_image;
        }else{
            $banner_image =  $category->banner_image;
        }

        return isset($banner_image) ? $banner_image : '';

    }

    public static function getImage($slug){
        return self::find()->where(['slug'=>$slug])->one()->image;
    }

    public static function getThumbnail($slug){
        return self::find()->where(['slug'=>$slug])->one()->thumbnail;
    }

    public static function getChildCategories($parent_id){
        return self::find()->where(['parent_id'=>$parent_id])->all();
    }

    public static function getProductNavChildCategories($parent_id){
        return self::find()->where(
            [
                'parent_id' => $parent_id,
                'is_nav'    => true,
                'is_active' => true,
                'is_brand'  => false,
            ]
        )->all();
    }

    public static function getUrl(){

    }
}
