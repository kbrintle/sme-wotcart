<?php

namespace common\models\utilities;

use Yii;

/**
 * This is the model class for table "media_temp".
 *
 * @property int $id
 * @property string $sku
 * @property string $value
 */
class MediaTemp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media_temp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku'], 'string', 'max' => 55],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Sku',
            'value' => 'Value',
        ];
    }
}
