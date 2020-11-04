<?php
/**
 * Based on:
 * @link https://www.github.com/hscstudio/yii2-cart
 * @copyright Copyright (c) 2016 HafidMukhlasin.com
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\wotcart;

interface ItemInterface{
    /** Triggered on cost calculation */
    const EVENT_COST_CALCULATION = 'costCalculation';

    /**
     * @return integer
     */
    public function getPrice();
    /**
     * @return string
     */
    public function getId();
    /**
     * @param bool $withDiscount
     * @return integer
     */
    public function getCost($withDiscount = true);
    /**
     * @param int $quantity
     */
    public function setQuantity($quantity);
    /**
     * @return int
     */
    public function getQuantity();
}