<?php

namespace common\models\core;

use Yii;
use common\models\core\query\SubregionsQuery;

/**
 * This is the model class for table "subregions".
 *
 * @property integer $id
 * @property integer $region_id
 * @property string $name
 * @property string $timezone
 * @property Regions $region
 *
 */
class Subregions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subregions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'integer'],
            [['name', 'timezone'], 'string', 'max' => 45],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'region_id' => 'Region ID',
            'name' => 'Name',
            'timezone' => 'Timezone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }

    /**
     * @inheritdoc
     * @return StudentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubregionsQuery(get_called_class());
    }
}
