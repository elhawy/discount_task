<?php

namespace Modules\Products\Repositories\Interfaces;

use Modules\Infrastructure\Repositories\Interfaces\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getProductsByNames(array $productsNames, array $withRelation);
}
