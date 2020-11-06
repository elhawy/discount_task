<?php

namespace Modules\Orders\Repositories;

use Modules\Infrastructure\Repositories\BaseRepository;
use Modules\Orders\Entities\Currency;
use Modules\Orders\Entities\Order;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Order;
    }

    public function getCurrencyByCode(string $currency)
    {
        return Currency::where('code', $currency)->first();
    }
    public function createCurrency(array $currencyData)
    {
        return Currency::create($currencyData);
    }
}
