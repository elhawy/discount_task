<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Discounts\Entities\Discount;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Orders\Entities\Order;
use Modules\Products\Entities\Product;

class ProductsOrder extends Model
{
    use Hashidable;
    use HasFactory;

    protected $table = 'products_orders';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected static function newFactory()
    {
        return \Modules\Orders\Database\factories\ProductsOrderFactory::new();
    }
}