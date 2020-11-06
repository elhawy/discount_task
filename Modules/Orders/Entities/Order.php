<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Products\Entities\Product;
use Modules\Orders\Entities\ProductsOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Orders\Entities\Currency;

class Order extends Model
{
    use Hashidable;
    use HasFactory;

    protected $table = 'orders';

    protected $guarded = [];

    public function productsOrder()
    {
        return $this->hasMany(ProductsOrder::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_orders');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
