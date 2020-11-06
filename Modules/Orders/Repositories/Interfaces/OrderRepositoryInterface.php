<?php

namespace Modules\Orders\Repositories\Interfaces;

use Modules\Orders\Entities\Currency;

interface OrderRepositoryInterface
{
    public function getCurrencyByCode(string  $currency);
}
