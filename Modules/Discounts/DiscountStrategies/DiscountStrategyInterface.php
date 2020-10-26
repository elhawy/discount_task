<?php

namespace Module\Discounts\DiscountStrategies;

interface DiscountStrategyInterface
{
    public function applyDiscount($price): double;
    public function checkDiscount(array $productsIDS = []) : boolean;
}
