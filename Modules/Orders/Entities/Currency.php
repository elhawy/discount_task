<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Products\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use Hashidable;
    use HasFactory;

    protected $table = 'currency';

    protected $guarded = [];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_id');
    }
}

