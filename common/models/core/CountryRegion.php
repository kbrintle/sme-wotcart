<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "country_region".
 *
 * @property int $id Region Id
 * @property string $country_id Country Id in ISO-2
 * @property string $code Region code
 * @property string $default_name Region Name
 */
class CountryRegion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country_region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['country_id'], 'string', 'max' => 4],
            [['code'], 'string', 'max' => 32],
            [['default_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Region ID',
            'country_id' => 'Country ID',
            'code' => 'Code',
            'default_name' => 'Default Name',
        ];
    }

    public static function getRegionById($region_id){
        return CountryRegion::find()->where(['id'=>$region_id])->one();
    }

}
