<?php

namespace backend\models;
use common\components\helpers\FormHelper;
use Yii;
use common\models\catalog\CatalogCategory;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;
use common\components\CurrentStore;


class CategoryForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $id;
    public $name;
    public $description;
    public $image;
    public $banner_image;
    public $thumbnail;
    public $is_active;
    public $is_deleted;
    public $is_homepage;
    public $is_nav;
    public $is_brand;
    private $media_dir = '/category/';
    private $category;



    public function rules()
    {
        return [
           // [['name'], 'unique'],
            [['name','is_active', 'is_nav', 'is_brand', 'is_homepage'], 'required'],
            [['description'], 'string'],
            [['is_active'], 'boolean'],
            [['image', 'banner_image', 'thumbnail'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg']
            ,
        ];
    }

    public function init(){
        $this->category = new CatalogCategory();
    }

    public function upload()
    {

        if ($this->validate()) {

            $uploadsPath = Yii::getAlias("@frontend") . '/web/uploads/category/';
            if($this->image){
                $this->image->saveAs($uploadsPath . $this->image->baseName . '.' . $this->image->extension);
            }
            if($this->banner_image) {
                $this->banner_image->saveAs($uploadsPath . $this->banner_image->baseName . '.' . $this->banner_image->extension);
            }
            if($this->thumbnail) {
                $this->thumbnail->saveAs($uploadsPath . $this->thumbnail->baseName . '.' . $this->thumbnail->extension);
            }
            return true;
        } else {
            return false;
        }
    }

    public function loadCategory($category){
        $this->category             = $category;
        $this->name                 = $category->name;
        $this->description          = $category->description;
        $this->image                = $category->image;
        $this->banner_image         = $category->banner_image;
        $this->thumbnail            = $category->thumbnail;
        $this->is_active            = $category->is_active;
        $this->is_nav               = $category->is_nav;
        $this->is_homepage          = $category->is_homepage;
        $this->is_brand             = $category->is_brand;
        $this->is_deleted           = false;
    }


    public function save($new = false){

        if($new){
            $this->category->slug       = FormHelper::getFormattedURLKey($this->name);
            $this->category->store_id   = CurrentStore::getStoreId();
            $this->category->created_at = time();
        }

        $this->category->name           = $this->name;
        $this->category->description    = $this->description;
        $this->category->image          = (isset($this->image->name)) ? $this->media_dir.$this->image->name : $this->category->image;
        $this->category->banner_image   = (isset($this->banner_image->name)) ? $this->media_dir.$this->banner_image->name : $this->category->image;
        $this->category->thumbnail      = (isset($this->thumbnail->name)) ? $this->media_dir.$this->thumbnail->name : $this->category->image;
        $this->category->is_active      = $this->is_active;
        $this->category->is_brand       = $this->is_brand;
        $this->category->is_deleted     = false;
        $this->category->is_nav         = $this->is_nav;
        $this->category->is_homepage    = $this->is_homepage;

        if( $this->category->save(false) ){
            return true;
        }

        return false;
    }


}