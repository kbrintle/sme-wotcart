<?php

namespace common\models\catalog;

use common\models\catalog\query\CatalogProductFeatureQuery;
use Yii;

/**
 * This is the model class for table "catalog_product_feature".
 *
 * @property integer $id
 * @property integer $feature_id
 * @property integer $product_id

 */
class CatalogProductFeature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_feature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['feature_id', 'product_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'feature_id' => 'Feature ID',
            'product_id' => 'Product ID',

        ];
    }
}
