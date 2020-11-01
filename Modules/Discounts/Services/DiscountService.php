<?php

namespace Modules\Discounts\Services;

use Modules\Discounts\Services\Interfaces\DiscountServiceInterface;
use Modules\Discounts\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Discounts\DiscountStrategies\AbstractDiscountStrategy;
use Modules\Discounts\Entities\Discount;

class DiscountService implements DiscountServiceInterface
{
    private $discountRepository;

    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function calculateDiscount(Discount $discount, float $price, array $countedProductIDS, int $productID) : float
    {
        $discountStrategy = AbstractDiscountStrategy::createDiscountObject($discount, $price, $countedProductIDS, $productID);
        $discountedPrice = $discountStrategy->applyDiscount();
        return $discountedPrice;
    }
}
