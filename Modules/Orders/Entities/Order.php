<?php

namespace Modules\Orders\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Infrastructure\Http\Traits\Hashidable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use Hashidable;
    protected $table = 'orders';
}
