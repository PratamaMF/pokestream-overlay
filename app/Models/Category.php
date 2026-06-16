<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use Loggable;
    use HasUuids;

    protected $table = 'categories';

    protected $fillable = ['category_name'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
