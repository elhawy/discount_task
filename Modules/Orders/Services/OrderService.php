<?php

namespace Modules\Orders\Services;

use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Orders\Services\nterfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
}
