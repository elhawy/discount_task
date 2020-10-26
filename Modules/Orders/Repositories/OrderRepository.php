<?php

namespace Modules\Orders\Repositories;

use Modules\Orders\Entities\Order;
use Modules\Orders\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Order;
    }
}
