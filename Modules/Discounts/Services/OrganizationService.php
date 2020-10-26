<?php

namespace Modules\Discounts\Services;

class DiscountService implements DiscountServiceInterface
{
    private $discountRepository;

    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }
}
