<?php

namespace common\models\utilities;

use Yii;

/**
 * This is the model class for table "descriptions".
 *
 * @property int $id
 * @property string $sku
 * @property string $short_description
 * @property string $description
 */
class Descriptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'descriptions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['short_description', 'description'], 'string'],
            [['sku'], 'string', 'max' => 155],
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
            'short_description' => 'Short Description',
            'description' => 'Description',
        ];
    }
}