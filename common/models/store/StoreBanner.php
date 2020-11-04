<?php

namespace common\models\store;

use common\models\store\query\StoreBannerQuery;

use Yii;

/**
 * This is the model class for table "store_flyer".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $image
 * @property string $content
 * @property string $title
 * @property string $sub_title
 * @property string $button_text
 * @property string $button_url
 * @property string $page_location
 * @property integer $starts_at
 * @property integer $end_at
 * @property integer $is_deleted
 * @property integer $is_active
 * @property integer $created_at
 * @property integer $modified_at
 */
class StoreBanner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_banner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'is_deleted', 'is_active', 'created_at', 'modified_at'], 'integer'],
            [['starts_at', 'ends_at'], 'safe'],
            [['image', 'page_location', 'button_url', 'button_text', 'content', 'title', 'sub_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_id' => 'Store ID',
            'image' => 'Image',
            'starts_at' => 'Start',
            'ends_at' => 'End',
            'page_location' => "Page Location",
            'is_deleted' => 'Is Deleted',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * @inheritdoc
     * @return StoreFlyerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StoreBannerQuery(get_called_class());
    }

    public static function getBannerByPageLocation($pageLocation, $storeId = 0, $multiple = false)
    {
        $banner = self::find()->where([
            'page_location' => $pageLocation,
            'is_deleted' => false,
            'is_active' => true,
            'store_id' => $storeId
        ]);

        if ($multiple) {
            $banner = $banner->all();
        } else {
            $banner = $banner->one();
        }

        if (!empty($banner)) {
            return $banner;
        } else {
            $banner = self::find()->where([
                'page_location' => $pageLocation,
                'is_deleted' => false,
                'is_active' => true,
                'store_id' => 0
            ]);

            if ($multiple) {
                $banner = $banner->all();
            } else {
                $banner = $banner->one();
            }
            if (!empty($banner)) {
                return $banner;
            } else {
                return new self();
            }
        }
    }

    public static function getBannerByImage($image, $storeId = 0)
    {
        $banner = self::find()->Where([
            'image' => $image,
            'is_deleted' => false,
            'is_active' => true,
            'store_id' => $storeId
        ])->one();

        if (!empty($banner)) {
            return $banner;
        }else{
            $banner = self::find()->Where([
                'image' => $image,
                'is_deleted' => false,
                'is_active' => true,
                'store_id' => 0
            ])->one();
            if (!empty($banner)) {
                return $banner;
            }else{
                return new self();
            }
        }
    }
}