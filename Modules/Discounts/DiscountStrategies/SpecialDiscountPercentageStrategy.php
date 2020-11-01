<?php

namespace Modules\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class SpecialDiscountPercentageStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount() : float
    {
        if ($this->checkDiscount()) {
            $qty = $this->calculateDiscountQty();
            return $qty * max($this->price - (($this->discount->amount / 100) * $this->price), 0);
        } else {
            return $this->price;
        }
    }

    public function checkDiscount() : bool
    {
        if (isset($this->countedCartProductIDS[$this->productID]) &&
            !empty($this->discount->specialDiscount) &&
            $this->discount->type == DiscountTypeLookups::SPECIAL_PERCENTAGE &&
            $this->discount->specialDiscount->qty <= $this->countedCartProductIDS[$this->discount->specialDiscount->id]) {
            return true;
        } else {
            return false;
        }
    }
}
