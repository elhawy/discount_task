<?php

namespace Modules\Discounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Products\Entities\Product;
use Modules\Discounts\Entities\SpecialDiscount;

class Discount extends Model
{
    use HasFactory;
    use Hashidable;

    protected $table = 'discounts';
    
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_discounts');
    }

    public function specialDiscount()
    {
        return $this->hasOne(SpecialDiscount::class);
    }

    protected static function newFactory()
    {
        return \Modules\Discounts\Database\factories\DiscountFactory::new();
    }
}
