<?php

namespace Module\Discounts\DiscountStrategies;

use Modules\Discounts\Entities\Discount;

class AbstractDiscountStrategy
{
    protected $discount;

    public function __construct(Discount $discount)
    {
        $this->discount = $discount;
    }
}
