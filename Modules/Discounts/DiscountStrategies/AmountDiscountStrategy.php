<?php

namespace Modules\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class AmountDiscountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{

    public function applyDiscount() : float
    {
        if ($this->checkDiscount()) {
            $qty = $this->calculateDiscountQty();
            return $qty * max($this->price - $this->discount->amount, 0);
        } else {
            return $this->price;
        }
    }

    public function checkDiscount() :bool
    {
        return ($this->discount->type == DiscountTypeLookups::AMOUNT);
    }
}
