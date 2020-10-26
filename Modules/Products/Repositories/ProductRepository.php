<?php

namespace Modules\Orders\Repositories;

use Modules\Orders\Entities\Product;
use Modules\Products\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Product;
    }
}
