<?php

namespace common\models\cms;

use common\models\cms\query\CmsPageQuery;
use common\models\core\Store;
use common\components\CurrentStore;
use Yii;

/**
 * This is the model class for table "cms_page".
 *
 * @property integer $id
 * @property string $title
 * @property string $template
 * @property string $url_key
 * @property string $content
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $author_id
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $is_active
 */
class CmsPage extends \yii\db\ActiveRecord
{

    public $templates = [
        // 'file_name_of_template' => 'Visible name of template'
        '_single_column' => 'Single Column'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cms_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'meta_description', 'meta_keywords'], 'string'],
            [['author_id', 'created_time', 'modified_time', 'is_active'], 'integer'],
            [['title', 'url_key'], 'string', 'max' => 155],
            [['template'], 'string', 'max' => 55],
        ];
    }

    public function trimUrlKey(){
        $res     = $this->url_key;
        $res     = self::stripExtraSpaces($res);
        $res     = preg_replace("/[^a-zA-Z ]/", "", $res);
        $res     = str_replace(' ', '-', $res);

        $this->url_key = $res;
    }

    public static function stripExtraSpaces($s)
    {
        $newstr = "";
        for($i = 0; $i < strlen($s); $i++)
        {
            $newstr .= substr($s, $i, 1);
            if(substr($s, $i, 1) == ' ')
                while(substr($s, $i + 1, 1) == ' ')
                    $i++;
        }
        return $newstr;
    }

    public function getStore(){
        return $this->hasOne(Store::className(), ['id' => 'store_id'])
            ->viaTable('cms_page_store', ['page_id' => 'id']);
    }


    public function getPageByUrlKey($url_key=null){
        if($url_key){
            $store_id = CurrentStore::getStoreId();

            return $this->hasOne(CmsPage::className(), ['id' => 'page_id'])
                ->viaTable('cms_page_store', ['store_id' => 'id'])
                ->andWhere([
                    'cmspage.url_key'   => $url_key,
                    'store.id'          => [0, $store_id]
                ]);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'template' => 'Template',
            'url_key' => 'Url Key',
            'content' => 'Content',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'author_id' => 'Author ID',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'is_active' => 'Is Active',
        ];
    }

    public function createStoreRelationship($store_id){
        $connection = Yii::$app->getDb();
        return $connection->createCommand()->insert('cms_page_store', [
                    'store_id'  => $store_id,
                    'page_id'   => $this->id,
                ])->execute();
    }
    public function removeStoreRelationship($store_id){
        $connection = Yii::$app->getDb();
        return $connection->createCommand()->delete('cms_page_store', [
                    'store_id'  => $store_id,
                    'page_id'   => $this->id,
                ])->execute();
    }

    /**
     * @inheritdoc
     * @return CmsPageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CmsPageQuery(get_called_class());
    }

    public static function getCmsPage($url_key){
        $url_parts = explode('/', ltrim(Yii::$app->request->url, '/'));

        $current_store = CurrentStore::getStore();
        return CmsPage::find()->byKey($url_key)->one();
//        if( $current_store && (count($url_parts) == 2) ){
//            $url_key = $url_parts[1];
//
//        }
        return null;
    }
}
