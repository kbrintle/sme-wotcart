<?php

namespace backend\models;

use Yii;
use common\components\CurrentStore;
use common\models\promotion\PromoImages;
use yii\base\Model;

class PromoImagesForm extends Model {

    public $image_1;
    public $image_1_url;
    public $image_1_order;
    public $image_1_link;
    public $image_1_title;

    public $image_2;
    public $image_2_url;
    public $image_2_order;
    public $image_2_link;
    public $image_2_title;

    public $image_3;
    public $image_3_url;
    public $image_3_order;
    public $image_3_link;
    public $image_3_title;

    public $image_4;
    public $image_4_url;
    public $image_4_order;
    public $image_4_link;
    public $image_4_title;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['image_1_url',
                'image_2_url',
                'image_3_url',
                'image_4_url',
                'image_1_order',
                'image_2_order',
                'image_3_order',
                'image_4_order',
                'image_1_link',
                'image_2_link',
                'image_3_link',
                'image_4_link',
                'image_1_title',
                'image_2_title',
                'image_3_title',
                'image_4_title'], 'string'],
            [['image_1', 'image_2', 'image_3', 'image_4'],
                'image',
                'skipOnEmpty'   => true,
                'extensions'    => 'png, jpg',
//                'minWidth'      => 960,
//                'maxWidth'      => 960,
//                'minHeight'     => 700,
//                'maxHeight'     => 700
            ]
        ];
    }

    public function loadImages($models){
        $i = 1;
        foreach($models as $model){
            $this->{"image_$i"."_url"}   = $model->image;
            $this->{"image_$i"."_order"} = $model->order;
            $this->{"image_$i"."_link"}  = $model->link;
            $this->{"image_$i"."_title"} = $model->title;
            $i++;
        }
    }

    public function getStoreId(){
        return CurrentStore::getStoreId() ? CurrentStore::getStoreId() : 0;
    }

    public function upload(){
        if( $this->validate() ){
            for( $i = 1; $i <= 4; $i++ ){
                if( $this->{"image_$i"} ){
                    $filename    = (string) crc32($this->{"image_$i"}->baseName).'.'.$this->{"image_$i"}->extension;
                    $file_exists = PromoImages::findOne([
                        'store_id' => $this->getStoreId(),
                        'image'    => "promo/$filename"
                    ]);
                    if( !$file_exists ){
                        $upload_dir  = "uploads/promo/$filename";
                        $path        = Yii::getAlias("@frontend")."/web/$upload_dir";

                        $this->{"image_$i"}->saveAs($path);
                        $this->{"image_$i"."_url"} = "promo/$filename";
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function save(){
        $store_id       = $this->getStoreId();
        $current_images = PromoImages::deactivateAll($store_id);

        for( $i = 1; $i <= 4; $i++ ){
            if( $this->{"image_$i"."_url"} ){
                $file_exists = PromoImages::findOne([
                    'store_id' => $this->getStoreId(),
                    'image'    => $this->{"image_$i"."_url"},
                    'link'     => $this->{"image_$i"."_link"}
                ]);
                if( !$file_exists ){
                    $promo_image            = new PromoImages();
                    $promo_image->store_id  = $store_id != 0 ? $store_id : NULL;
                    $promo_image->image     = $this->{"image_$i"."_url"};
                    $promo_image->order     = $i;
                    $promo_image->link      = $this->{"image_$i"."_link"};
                    $promo_image->active    = 1;
                } else {
                    $promo_image            = $file_exists;
                    $promo_image->order     = $this->{"image_$i"."_order"};
                    $promo_image->link      = $this->{"image_$i"."_link"};
                    $promo_image->active    = 1;
                }
                $promo_image->save();
            }
        }
    }

}