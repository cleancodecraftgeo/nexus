<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrderStatus;


class Order extends Model
{
    //
    use HasUlids;

    protected $fillable = [
        'total',
        'status',
    ];

    public function items():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function casts():array
    {
        return [
            'status'=>OrderStatus::class
        ];
    }

}
