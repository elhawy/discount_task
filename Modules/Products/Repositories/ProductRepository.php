<?php

namespace Modules\Products\Repositories;

use Modules\Products\Entities\Product;
use Modules\Products\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Infrastructure\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Product;
    }

    public function getProductsByNames(array $productsNames, array $withRelation)
    {
        return $this->model->whereIN('name', $productsNames)->with($withRelation)->get();
    }
}
