<?php

namespace Modules\Discounts\Repositories;

use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Repositories\Interfaces\DiscountRepositoryInterface;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Discount;
    }
}
