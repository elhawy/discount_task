<?php

namespace Modules\Discounts\Services\Interfaces;

use Modules\Discounts\Entities\Discount;

interface DiscountServiceInterface
{
    public function calculateDiscount(Discount $discount, float $price, array $countedProductIDS, int $productID) : float;
}
