<?php

namespace App\Models;

// use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasUuids;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'price_at_purchase',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->qty * $this->price_at_purchase;
    }
}
