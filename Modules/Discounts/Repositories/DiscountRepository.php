<?php

namespace Modules\Discounts\Repositories;

use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Infrastructure\Repositories\BaseRepository;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Discount;
    }
}
