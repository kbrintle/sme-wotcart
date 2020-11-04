<?php

namespace common\models\core;

use common\components\CurrentStore;
use Yii;
use common\models\core\query\CoreConfigQuery;

/**
 * This is the model class for table "core_config".
 *
 * @property integer $config_id
 * @property integer $store_id
 * @property string $path
 * @property string $value
 */
class CoreConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'core_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['value'], 'string'],
            [['path'], 'string', 'max' => 255],
            [['store_id', 'path'], 'unique', 'targetAttribute' => ['store_id', 'path'], 'message' => 'The combination of Scope, Store ID and Path has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_id' => 'Config ID',
            'store_id' => 'Store ID',
            'path' => 'Path',
            'value' => 'Value',
        ];
    }

    /**
     * @inheritdoc
     * @return CoreConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoreConfigQuery(get_called_class());
    }

    public static function getStoreConfig($path, $store_id = null){
        if(!$store_id){
            $store_id = CurrentStore::getStoreId();
        }

        $config = self::find()->where(['path'=>$path, 'store_id'=>$store_id])->one();


        if(isset($config) && !empty($config)){
            return $config->value;
        }else{
            $config = self::find()->where(['path'=>$path, 'store_id'=>0])->one();
        }
        return isset($config->value) ? $config->value : '';
    }

    public static function saveConfig($path, $value, $store_id = null){
        if($store_id == null){
            $store_id = CurrentStore::getStoreId();
        }
        $config = CoreConfig::find()->where(['path'=>$path, 'store_id'=>$store_id])->one();
        if($config){
            $config->value = $value;
        }else{
            $config = new CoreConfig();
            $config->value    = $value;
            $config->path     = $path;
            $config->store_id = $store_id;
        }

        if($config->save(false)){
            return true;
        }
        print_r($config->errors);
        return false;
    }

    public static function getStoreConfigTab($path){
        $path_secs = explode('/', $path);
        return $path_secs[0];
    }
    public static function getStoreConfigSection($path){
        $path_secs = explode('/', $path);
        return $path_secs[1];
    }
    public static function getStoreConfigInput($path){
        $path_secs = explode('/', $path);
        return $path_secs[2];
    }
}