<?php

namespace common\models\store;

use common\components\CurrentStore;
use common\models\core\Store;
use Yii;

/**
 * This is the model class for table "store_event".
 *
 * @property int $id
 * @property string $title
 * @property string $featured_image_path
 * @property string $slug
 * @property string $event_date
 * @property string $event_start_date
 * @property string $event_end_date
 * @property int $store_id
 * @property string $content
 * @property int $author_id
 * @property int $is_active
 * @property int $is_deleted
 * @property int $created_at
 * @property int $modified_at
 */
class StoreEvent extends \yii\db\ActiveRecord
{


    public $featured_image;
    public $remove_featured_image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_date', 'event_start_date', 'event_end_date'], 'safe'],
            [['store_id', 'author_id', 'is_active', 'is_deleted', 'created_at', 'modified_at'], 'integer'],
            [['content'], 'string'],
            [['title', 'slug'], 'string', 'max' => 155],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'event_date' => 'Event Date',
            'event_start_date' => 'Event Start Date',
            'event_end_date' => 'Event End Date',
            'store_id' => 'Store ID',
            'content' => 'Content',
            'author_id' => 'Author ID',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    public static function getEvents($limit=1000){
        return self::find()->where([
            'store_id' => [Store::NO_STORE, CurrentStore::getStoreId()],
            'is_active' => true
        ])
            ->orderby('event_date')
            ->limit($limit)
            ->all();
    }
    public static function getEventDateHtml($id){
        $event = self::findOne($id);

        if($event->event_start_date == $event->event_end_date || empty($event->event_end_date)){
            return date("F j, Y, g:i a", strtotime($event->event_start_date))." EST";
        }else{
            return date("F j, Y, g:i a", strtotime($event->event_start_date))." EST - " .date("F j, Y, g:i a", strtotime($event->event_end_date)) . " EST";
        }

    }


}