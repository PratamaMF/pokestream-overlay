<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUuids;
    use Loggable;
    protected $table = 'orders';
    protected $fillable = ['customer_name', 'status'];

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function getTotalAttribute(): int
    {
        return $this->orderDetails->sum('subtotal');
    }
}
