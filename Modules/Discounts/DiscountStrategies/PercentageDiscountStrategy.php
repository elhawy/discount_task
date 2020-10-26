<?php

namespace Module\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;

class PercentageDiscountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applayDiscount($price)
    {
        return max($price - (($this->discount->amount / 100) * $price), 0);
    }
}
