<?php

namespace frontend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogProductReview;
use Yii;
use yii\base\Model;


class CreateReviewForm extends Model{
    //model fields
    private $_user;
    private $_store_id;
    private $_review_errors;

    //form fields
    public $product_id;
    public $title;
    public $detail;
    public $rating;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['title', 'detail'], 'string'],
            [['rating', 'product_id'], 'integer'],
            [['title', 'detail', 'rating', 'product_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [];
    }

    public function init(){
        $this->_user        = Yii::$app->user;
        $this->_store_id    = CurrentStore::getStoreId();
    }


    public function getReviewErrors(){
        return $this->_review_errors;
    }

    public function save(){
        $review             = new CatalogProductReview();
        $review->product_id = $this->product_id;
        $review->title      = $this->title;
        $review->detail     = $this->detail;
        $review->rating     = $this->rating;
        $review->approved   = false;
        $review->created_at = time();
        $review->customer_id= $this->_user->id;
        $review->store_id   = $this->_store_id;

        if( !$review->save() ){
            $this->_review_errors = $review->errors;
            return null;
        }
        return $review;
    }
}
