<?php

namespace Modules\Products\Services;

use Modules\Products\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Products\Services\nterfaces\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
}
