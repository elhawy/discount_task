<?php

namespace Module\Discounts\DiscountStrategies;

use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\DiscountStrategies\DiscountStrategyInterface;

class SpecialDiscountAmountStrategy extends AbstractDiscountStrategy implements DiscountStrategyInterface
{
    public function applayDiscount($price)
    {
        return $price - $this->discount->amount;
    }

    public function checkSpecialDiscount($productsIDS)
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
