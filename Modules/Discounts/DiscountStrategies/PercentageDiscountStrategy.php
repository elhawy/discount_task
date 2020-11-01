<?php

namespace Modules\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class PercentageDiscountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount() : float
    {
        if ($this->checkDiscount()) {
            $qty = $this->calculateDiscountQty();
            return $qty * max($this->price - ($this->price * ($this->discount->amount / 100)), 0);
        } else {
            return $this->price;
        }
    }

    public function checkDiscount() : bool
    {
        return ($this->discount->type == DiscountTypeLookups::PERCENTAGE);
    }
}
