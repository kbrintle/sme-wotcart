<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product_relation".
 *
 * @property integer $product_id_1
 * @property integer $product_id_2
 * @property integer $type_id
 */
class CatalogProductRelation extends \yii\db\ActiveRecord
{

    const LINK_RELATED  = '1';
    const LINK_CROSSELL = '2';

    public static function tableName()
    {
        return 'catalog_product_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id_1', 'product_id_2','type_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id_1' => 'Parent ID',
            'product_id_2' => 'Child ID',
             'type_id' => 'Type ID'
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\catalog\models\query\CatalogProductRelationQuery the active query used by this AR class.
     */

    public static function find($overRideScope = false )
    {
        $query =  new \common\models\catalog\query\CatalogProductRelationQuery(get_called_class());

        if( !$overRideScope )
            $query->store();

        return $query;
    }
}