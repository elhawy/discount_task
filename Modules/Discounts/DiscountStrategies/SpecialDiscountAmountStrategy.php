<?php

namespace Modules\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;
use Modules\Discounts\Entities\Lookups\DiscountTypeLookups;

class SpecialDiscountAmountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applyDiscount() : float
    {
        if ($this->checkDiscount()) {
            $qty = $this->calculateDiscountQty();
            return $qty * ($this->price - $this->discount->amount);
        } else {
            return $this->price;
        }
    }

    public function checkDiscount() : bool
    {
        if (isset($this->countedCartProductIDS[$this->productID]) &&
            !empty($this->discount->specialDiscount) &&
            $this->discount->type == DiscountTypeLookups::SPECIAL_AMOUNT &&
            $this->discount->specialOffer->qty <= $this->countedCartProductIDS[$this->discount->specialDiscount->id]) {
            return true;
        } else {
            return false;
        }
    }
}
