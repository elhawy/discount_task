<?php

namespace Modules\Discounts\DiscountStrategies;

interface DiscountStrategyInterface
{
    public function applyDiscount(): float;
    public function checkDiscount() : bool;
}
