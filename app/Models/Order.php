<?php

namespace App\Models;

use App\Enums\Currency;
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

        'first_name',
        'last_name',
        'phone',
        'email',
        'city',
        'address',
        'postal_code',
        'notes',
        'total',
        'status',
        'currency_code',
        'exchange_rate'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'currency_code' => Currency::class,
            'total' => 'decimal:2',
            'exchange_rate' => 'decimal:4',
        ];
    }
}
