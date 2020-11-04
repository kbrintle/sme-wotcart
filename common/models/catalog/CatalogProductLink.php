<?php

namespace common\models\catalog;

use Yii;

/**
 * This is the model class for table "catalog_product_link".
 *
 * @property int $link_id Link ID
 * @property int $product_id Product ID
 * @property int $linked_product_id Linked Product ID
 * @property int $link_type Link Type ID
 *
 * @property CatalogProductLinkType $linkType
 * @property CatalogProductLinkAttributeDecimal[] $catalogProductLinkAttributeDecimals
 * @property CatalogProductLinkAttribute[] $productLinkAttributes
 * @property CatalogProductLinkAttributeInt[] $catalogProductLinkAttributeInts
 * @property CatalogProductLinkAttribute[] $productLinkAttributes0
 * @property CatalogProductLinkAttributeVarchar[] $catalogProductLinkAttributeVarchars
 * @property CatalogProductLinkAttribute[] $productLinkAttributes1
 */
class CatalogProductLink extends \yii\db\ActiveRecord
{
    const LINK_GROUPED  = 'grouped';
    const LINK_RELATED  = 'related';
    const LINK_UPSELL   = 'upsell';
    const LINK_CROSSELL = 'crosssell';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog_product_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'linked_product_id'], 'integer'],
            [['product_id', 'linked_product_id', 'link_type'], 'unique', 'targetAttribute' => ['link_type', 'product_id', 'linked_product_id']],
            [['link_type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'link_id' => 'Link ID',
            'product_id' => 'Product ID',
            'linked_product_id' => 'Linked Product ID',
            'link_type' => 'Link Type',
        ];
    }

}