<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    //
    use HasUlids;

    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    public function items():HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function casts():array
    {
        return [
            'status'=>OrderStatus::class
        ];
    }

}
