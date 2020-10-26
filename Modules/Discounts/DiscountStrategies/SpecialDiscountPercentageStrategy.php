<?php

namespace Module\Discounts\DiscountStrategies;

class SpecialDiscountPercentageStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applayDiscount($price)
    {
        return max($price - (($this->discount->amount / 100) * $price), 0);
    }

    public function checkDiscount($productsIDS)
    {
        $countedIDS = array_count_values($productsIDS);
        if (!$this->discount->specialOffers->isEmpty()) {
            foreach ($this->discount->specialOffers as $specialOffer) {
                if (!in_array($specialOffer->id, $productsIDS) ||
                    ($specialOffer->qty != $countedIDS[$specialOffer->id])) {
                    return false;
                } else {
                    continue;
                }
            }
        }
        return true;
    }
}
