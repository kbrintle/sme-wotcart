<?php

namespace common\models\core;


use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $name
 */
class StoreGroup extends \yii\db\ActiveRecord
{
   /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',

        ];
    }


}
