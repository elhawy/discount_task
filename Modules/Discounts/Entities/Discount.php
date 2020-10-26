<?php

namespace Modules\Discounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Discount extends Model
{
    use Hashidable;
    protected $table = 'orders';
}
