<?php

namespace Modules\Orders\Services\Interfaces;

interface OrderServiceInterface
{
    public function createOrder(array $cart): array;
}
