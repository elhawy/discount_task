<?php


namespace Modules\Products\Services\Interfaces;

use Modules\Products\Entities\Product;

interface ProductServiceInterface
{
    public function getProductsByNames(array $productsNames, array $withRelation);
}
