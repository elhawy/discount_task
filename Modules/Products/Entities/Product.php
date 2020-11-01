<?php

namespace Modules\Products\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Discounts\Entities\Discount;

class Product extends Model
{
    use HasFactory;
    use Hashidable;
    use HasFactory;

    protected $guarded = [];

    protected $table = 'products';

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'products_discounts');
    }

    protected static function newFactory()
    {
        return \Modules\Products\Database\factories\ProductFactory::new();
    }
}
