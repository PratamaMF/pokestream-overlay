<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasUuids;
    use Loggable;
    
    protected $table = 'notes';

    protected $fillable = ['title', 'description'];

}
