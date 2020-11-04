<?php

namespace frontend\models;

use common\components\CurrentStore;
use common\models\catalog\CatalogStoreProduct;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class GlobalSearchForm extends Model
{

    const SEARCH_TYPE_EXPANDED = 'expanded';
    const SEARCH_TYPE_SIMPLE = 'simple';

    public $keyword;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword'], 'string'],
            [['keyword'], 'required']
        ];
    }

    public function getSearchResults()
    {
        $output = [];

        if ($this->keyword) {

            if (Yii::$app->params['search_type'] == self::SEARCH_TYPE_EXPANDED) {
                $keywords = explode(' ', $this->keyword);
                $clause = "";
                $i = 0;
                foreach ($keywords as $keyword) {
                    if (sizeof($keywords) > 1) {
                        if ($i == 0) {
                            $clause = "AND (";
                        }
                        if ($i > 0) {
                            $clause .= "or ";
                        }
                        $clause .= " `catalog_attribute_value`.`value` LIKE '%$keyword%' ";
                        if ($i == sizeof($keywords) - 1) {
                            $clause .= ")";
                        }
                    } else {
                        $clause = " AND `catalog_attribute_value`.`value` LIKE '%$this->keyword%' ";
                    }
                    $i++;
                }
            } else {
                $clause = "AND REPLACE(REPLACE(REPLACE(LOWER(`catalog_attribute_value`.`value`), '-', ''), ')', ''), '(', '')
                LIKE 
                REPLACE(REPLACE(REPLACE(LOWER('%$this->keyword%'), '-', ''), ')', ''), '(', '')";
            }

            //get Products
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
            SELECT `catalog_product`.*
            FROM `catalog_product`
            LEFT JOIN `catalog_attribute_value` ON `catalog_product`.`id` = `catalog_attribute_value`.`product_id`
            LEFT JOIN `catalog_brand` ON `catalog_product`.`brand_id` = `catalog_brand`.`id`
            LEFT JOIN `catalog_attribute` ON `catalog_attribute`.`id` = `catalog_attribute_value`.`attribute_id`
            WHERE (`catalog_product`.`parent_id` IS NULL)
            AND (`catalog_product`.`type` NOT IN ('child-simple'))
            AND `catalog_attribute`.`slug` IN ('name', 'sku', 'meta-keywords', 'expired-skus')
            $clause
            GROUP BY `catalog_product`.`id`
            ORDER BY `catalog_attribute`.`id` ASC
            ");

            $products = $command->queryAll();
            $catalog_product_ids = [];
            foreach ($products as $product) {
                if (CatalogStoreProduct::findOne(["product_id" => $product['id'], 'store_id' => CurrentStore::getStoreId()])) {
                    $catalog_product_ids[] = $product['id'];
                }
            }
            $output = $catalog_product_ids;
        }
        return $output;
    }
}