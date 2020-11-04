<?php

namespace common\models\promotion;

use Yii;
use common\models\core\Store;
use common\models\promotion\query\PromoImagesQuery;
use common\components\CurrentStore;

/**
 * This is the model class for table "promo_images".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $store_id
 * @property string $image
 * @property string $title
 * @property integer $order
 * @property string $link
 * @property integer $active
 *
 * @property Store $store
 */
class PromoImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promo_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'store_id', 'order', 'active'], 'integer'],
            [['image', 'link', 'title'], 'string'],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'store_id' => 'Store ID',
            'image' => 'Image',
            'title' => 'Title',
            'order' => 'Order',
            'active' => 'Active'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    public static function deactivateAll($store_id){
        $store_id = $store_id != 0 ? $store_id : NULL;

        $images = self::findAll([
            'store_id' => $store_id,
            'active'   => true
        ]);

        foreach( $images as $image ){
            $image->active = 0;
            $image->save();
        }

        return true;
    }

    /**
     * @inheritdoc
     * @return PromoImagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PromoImagesQuery(get_called_class());
    }
}
