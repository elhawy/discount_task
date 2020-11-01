<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Products\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use Hashidable;
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_orders');
    }
}
