<?php

namespace common\models\core;

use Yii;
use common\models\core\query\RegionsQuery;

/**
 * This is the model class for table "regions".
 *
 * @property integer $id
 * @property string $iso
 * @property string $iso3
 * @property string $fips
 * @property string $country
 * @property string $continent
 * @property string $currency_code
 * @property string $currency_name
 * @property string $phone_prefix
 * @property string $postal_code
 * @property string $languages
 * @property string $geonameid
 *
 * @property Subregions[] $subregions
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso', 'iso3', 'fips', 'country', 'continent', 'currency_code', 'currency_name', 'phone_prefix', 'postal_code', 'languages', 'geonameid'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iso' => 'Iso',
            'iso3' => 'Iso3',
            'fips' => 'Fips',
            'country' => 'Country',
            'continent' => 'Continent',
            'currency_code' => 'Currency Code',
            'currency_name' => 'Currency Name',
            'phone_prefix' => 'Phone Prefix',
            'postal_code' => 'Postal Code',
            'languages' => 'Languages',
            'geonameid' => 'Geonameid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubregions()
    {
        return $this->hasMany(Subregions::className(), ['region_id' => 'id']);
    }

    /**
     * @return \app\models\RegionsQuery
     */
    public static function find()
    {
        return new RegionsQuery(get_called_class());
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public static function display($id)
    {
        $model = self::find()->where([ 'id' => $id ])->one();

        return !empty( $model ) ? $model->country : '';
    }

    /**
     * Put the US in front of all other countries
     * @return \app\models\Programs[]|array
     */
    public static function forDropdown()
    {
        $regions = Regions::find()->abc()->asArray()->all();

        foreach( $regions as $key => $region ) {
            if( $region['iso'] == 'US' ){
                $first = $region;
                unset( $regions[ $key ] );
            }
        }

        array_unshift($regions, $first);

        return $regions;
    }
}
