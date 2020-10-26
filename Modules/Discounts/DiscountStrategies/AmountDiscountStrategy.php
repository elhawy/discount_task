<?php

namespace Module\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;

class AmountDiscountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{

    public function applayDiscount($price)
    {
        return max($price - $this->discount->amount, 0);
    }
}
