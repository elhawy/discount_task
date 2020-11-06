<?php

namespace Modules\Orders\Services\Interfaces;

use Modules\Orders\Entities\Order;

interface OrderServiceInterface
{
    public function createOrder(array $cart, $currency): Order;
    public function convertOrderPrice(int $amount, string $to): array;
}
