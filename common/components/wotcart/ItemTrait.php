<?php
/**
 * Based on:
 * @link https://www.github.com/hscstudio/yii2-cart
 * @copyright Copyright (c) 2016 HafidMukhlasin.com
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\wotcart;
use yii\base\Component;
use yii\base\Object;

trait ItemTrait{
    protected $_quantity;

    public function getQuantity(){
        return $this->_quantity;
    }
    public function setQuantity($quantity){
        $this->_quantity = $quantity;
    }

    public function getCost($withDiscount = true){
        $price = $this->getPrice();
        if( is_array($price) )
            $price = $price['price'];

        $cost = $this->getQuantity() * $price;
//        $costEvent = new CostCalculationEvent([
//            'baseCost' => $cost,
//        ]);
//        if ($this instanceof Component)
//            $this->trigger(ItemInterface::EVENT_COST_CALCULATION, $costEvent);
//
//        // We may want to use this later...
//        if ($withDiscount) {
//            $discount = CartController::getPromoDiscount();
//            $cost     = max(0, $cost - (isset($discount) ? $discount : 0));
//        }
        return $cost;
    }
}