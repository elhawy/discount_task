<?php

namespace Modules\Products\Services;

use Modules\Products\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Products\Services\Interfaces\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductsByNames(array $productsNames, array $withRelation)
    {
        return $this->productRepository->getProductsByNames($productsNames, $withRelation);
    }
}
