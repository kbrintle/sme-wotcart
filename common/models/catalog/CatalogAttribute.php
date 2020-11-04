<?php

namespace common\models\catalog;

use common\components\CurrentStore;
use common\models\core\Store;
use common\models\catalog\query\CatalogAttributeQuery;
use yii\db\Expression;

/**
 * This is the model class for table "catalog_attribute".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $category_id
 * @property integer $type_id
 * @property string $label
 * @property string $slug
 * @property integer $is_filterable
 * @property integer $is_editable
 * @property integer $is_product_view
 * @property integer $filter_sort
 * @property integer $product_view_sort
 * @property integer $visible_on
 * @property integer $created_at
 * @property integer $modified_at
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $is_default
 * @property CatalogAttributeType $type
 * @property CatalogAttributeSetCategory $id0
 * @property CatalogAttributeOption $catalogAttributeOption
 * @property CatalogAttributeSetAttribute $catalogAttributeSetAttribute
 * @property CatalogAttributeValue $catalogAttributeValue
 */
class CatalogAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'type_id', 'label', 'slug', 'created_at'], 'required'],
            [['store_id', 'category_id', 'type_id', 'is_filterable', 'is_editable', 'visible_on', 'created_at', 'modified_at', 'is_active', 'is_deleted', 'is_default', 'is_product_view', 'filter_sort', 'product_view_sort'], 'integer'],
            [['label', 'slug'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttributeSetCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogAttributeType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'store_id'          => 'Store ID',
            'category_id'       => 'Category ID',
            'type_id'           => 'Type ID',
            'label'             => 'Label',
            'slug'              => 'Slug',
            'product_view_sort' => 'Product View Sort',
            'is_product_view'   => 'Visable on Product Page',
            'is_filterable'     => 'Is Filterable',
            'is_editable'       => 'Is Editable',
            'visible_on'        => 'Appears On',
            'created_at'        => 'Created At',
            'modified_at'       => 'Modified At',
            'is_active'         => 'Is Active',
            'is_deleted'        => 'Is Deleted',
            'is_default'        => 'Is Default',
        ];
    }

    public function isVisible($product_type) {
        $permissions[0] = ['configurable', 'simple', 'child-simple'];
        $permissions[1] = ['configurable'];
        $permissions[2] = ['simple', 'child-simple'];

        return in_array($product_type, $permissions[$this->visible_on]);
    }

    public static function getAttributesArray($product_id) {
        $values = CatalogAttributeValue::find()
            ->where(['IN', 'id', CatalogAttributeValue::find()
                ->select('id')
                ->where([
                    'product_id' => $product_id,
                    'store_id'   => [Store::NO_STORE, CurrentStore::getStoreId()]
                ])
                ->andWhere(['NOT IN', 'attribute_id', CatalogAttribute::find()
                    ->select('id')
                    ->where([
                        'slug' => ['description', 'visible', 'price', 'active', 'base-image', 'sku', 'name', 'mattress-construction']
                    ])
                ])
                ->orderBy(['store_id' => SORT_DESC])
            ])
            ->groupBy([new Expression('attribute_id DESC')])
            ->orderBy(['id' => SORT_DESC])
            ->all();



        foreach ($values as $value) {
            $attribute = self::findOne($value->attribute_id);
            if ($attribute->type_id == CatalogAttributeType::SELECT) {
                if( $option = CatalogAttributeOption::findOne([
                    'id' => $value->value]) ){
                    $attributes[$attribute->label] = $option->value;
                }
            }elseif($attribute->type_id == CatalogAttributeType::BOOLEAN) {
                $attributes[$attribute->label] = ($value->value) ? 'Yes' : 'No';
            }else {
                $attributes[$attribute->label] = $value->value;
            }
        }

        return isset($attributes) ? $attributes : null;
    }

    public static function getProductPageAttributesArray($product_id) {
        $attributesModel = CatalogAttribute::find()
            ->where([
                'is_product_view' => true
            ])
            ->orderBy(['product_view_sort'=>SORT_ASC])
            ->all();

        $attributes = [];
        foreach ($attributesModel as $attribute){



            $value = CatalogAttributeValue::find()->where([
                'product_id'    => $product_id,
                'attribute_id'  => $attribute->id,
                'store_id'   => [Store::NO_STORE, CurrentStore::getStoreId()]
            ])->one();


            if ($attribute->type_id == CatalogAttributeType::SELECT) {
                if( isset($value->value) && !empty($value->value)) {
                    if ($option = CatalogAttributeOption::findOne(['id' => $value->value])) {
                        $attributes[$attribute->label] = $option->value;
                    }
                }
            } elseif ($attribute->type_id == CatalogAttributeType::BOOLEAN) {
                $attributes[$attribute->label] = (!empty($value->value)) ? 'Yes' : 'No';
            } else {
                $attributes[$attribute->label] = $value->value;
            }
        }

        return isset($attributes) ? $attributes : null;
    }

    public static function getAttributeBySlug($slug){
        return CatalogAttribute::find()->where(['slug'=>$slug])->one();

    }

    public static function getActiveAttribute(){
        return CatalogAttribute::find()->where(['slug'=>'active'])->one();

    }

    public static function getOptionsBySlug($slug) {
        $attribute = CatalogAttribute::getAttributeBySlug($slug);

        if ($attribute) {
            $options = CatalogAttributeOption::find()->where([
                'attribute_id' => $attribute->id,
                'is_active'    => true

            ])->orderBy(['order'=>SORT_ASC])->all();

            if ($options)
                return $options;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CatalogAttributeType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(CatalogAttributeSetCategory::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeOption()
    {
        return $this->hasOne(CatalogAttributeOption::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeSetAttribute()
    {
        return $this->hasOne(CatalogAttributeSetAttribute::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalogAttributeValue()
    {
        return $this->hasOne(CatalogAttributeValue::className(), ['id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\catalog\query\CatalogAttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CatalogAttributeQuery(get_called_class());
    }
}
