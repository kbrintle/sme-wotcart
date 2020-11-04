<?php

namespace common\models\blog;

use common\components\CurrentStore;
use common\models\catalog\CatalogCategory;
use Yii;
use common\models\blog\query\BlogQuery;
/**
 * This is the model class for table "blog".
 *
 * @property integer $post_id
 * @property string $title
 * @property string $post_content
 * @property integer $status
 * @property string $created_time
 * @property string $update_time
 * @property string $identifier
 * @property string $user
 * @property string $update_user
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $comments
 * @property string $tags
 * @property string $short_content
 */
class Blog extends \yii\db\ActiveRecord{

    public $category_id;
    public $featured_image;
    public $remove_featured_image;

    public $new_category;
    public $new_category_name;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'title', 'post_content', 'tags'], 'required'],
            [['post_content', 'meta_keywords', 'meta_description', 'tags', 'short_content'], 'string'],
            [['status', 'comments', 'category_id'], 'integer'],
            [['created_time', 'update_time'], 'safe'],
            [['title', 'identifier', 'user', 'update_user'], 'string', 'max' => 255],
            [['identifier'], 'unique'],

            [['remove_featured_image'], 'boolean'],
            [['featured_image_path'], 'string'],
            [['featured_image'],
                'image',
                'skipOnEmpty'   => true,
                'extensions'    => 'png, jpg, gif, jpeg'
            ],

            [['new_category'], 'boolean'],
            [['new_category_name'], 'string'],
            ['new_category_name', 'required', 'when' => function ($model){
                    return $model->new_category;
                }, 'whenClient' => "function(attribute, value){
                    return $('#blog-new_category').is(':checked');
                }"
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'title' => 'Title',
            'post_content' => 'Post Content',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'update_time' => 'Update Time',
            'identifier' => 'Identifier',
            'user' => 'User',
            'update_user' => 'Update User',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'comments' => 'Comments',
            'tags' => 'Tags',
            'short_content' => 'Short Content',
        ];
    }

    public function getCategory(){
        return $this->hasOne(BlogCategory::className(), ['cat_id' => 'cat_id'])
            ->viaTable('blog_post_category', ['post_id' => 'post_id']);
    }

    public function setIdentifier($attempt=0){
        $identifier =  preg_replace("/(\W)+/", "-", strtolower($this->title));

        if( $attempt > 0 ){
            $this->identifier = $identifier."-".$attempt;
        }

        $existing_post = Blog::find()->where([
            'identifier' => $identifier
        ])->one();

        if( $existing_post ){
            $this->setIdentifier($attempt++);
        }

        $this->identifier = $identifier;
    }


    /**
     * Create Post Excerpt from a Word Count
     *
     * @param int $word_count
     * @return string
     */
    public function getExcerpt($word_count=25){
        $output = '';

        if( $this->short_content ){ //is there a pre-defined excerpt?
            $output = $this->short_content;
        }else{
            if( $this->post_content ){  //is there post_content?
                if( !is_nan(intval($word_count)) ){ //validate if $word_count is a number
                    if( str_word_count($this->post_content, 0) > $word_count ){ //does post_content have words?
                        $words  = str_word_count($this->post_content, 2);
                        $pos    = array_keys($words);
                        $text   = substr($this->post_content, 0, $pos[$word_count]) . '[...]';
                        $output = $text;
                    }
                }
            }
        }

        return $output;
    }


    /**
     * Get array of Tags
     *
     * @return array
     */
    public function getTagList(){
        $output = [];

        if( $this->tags )
            $output = array_map('trim', explode(',', $this->tags));

        return $output;
    }

    public function getSocialUrl(){
        $current_store = CurrentStore::getStore();
        $current_store_path = 'national';
        if( $current_store ){
            $current_store_path = $current_store->url;
        }

        return \yii\helpers\Url::to(["$current_store_path/blog/detail/$this->identifier"], true);
    }

    /**
     * @inheritdoc
     * @return BlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlogQuery(get_called_class());
    }
}