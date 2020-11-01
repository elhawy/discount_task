<?php

namespace Modules\Discounts\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Modules\Discounts\Entities\Discount;
use Modules\Discounts\Entities\SpecialDiscountFactory;

class SpecialDiscount extends Model
{
    use HasFactory;
    use Hashidable;

    protected $table = 'special_discount';
    
    protected $guarded = [];

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory()
    {
        return \Modules\Discounts\Database\factories\SpecialDiscountFactory::new();
    }
}
