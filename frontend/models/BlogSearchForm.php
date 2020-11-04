<?php

namespace frontend\models;


use common\models\blog\Blog;
use Yii;
use yii\base\Model;

class BlogSearchForm extends Model{
    public $keyword;

    private $_posts;


    public function rules(){
        return [
            [['keyword'], 'string'],
            [['keyword'], 'trim']
        ];
    }

    public function init(){

    }

    public function search(){
        $query = Blog::find();

        $query->where([ //find published
            'status' => '1'
        ]);

        if( $this->keyword ){
            $keywords = array_map('trim', explode(',', $this->keyword));

            if( count($keywords) > 0 ){
                $query->joinWith('category cat');

                $keyword_query = ['or'];
                foreach($keywords as $keyword){
                    $keyword_query[] = ['like', 'blog.title', $keyword];
                    $keyword_query[] = ['like', 'post_content', $keyword];
                    $keyword_query[] = ['like', 'short_content', $keyword];
                    $keyword_query[] = ['like', 'user', $keyword];
                    $keyword_query[] = ['like', 'tags', $keyword];
                    $keyword_query[] = ['like', 'cat.title', $keyword];
                }

                $query->andWhere($keyword_query);
            }
        }

        $query->orderBy('created_time DESC'); //order by created date (published)

        $this->_posts = $query->all();  //set the queried posts
    }

    public function getPosts(){
        return $this->_posts;
    }
}
