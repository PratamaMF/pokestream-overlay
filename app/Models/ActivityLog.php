<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table = 'activity_logs';

    protected $fillable = ['activity', 'module', 'details'];
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'details' => 'array'
    ];

    public static function hasLog(string $activity, string $module, ?array $details = null): void
    {
        self::create([
            'activity' => $activity,
            'module' => $module,
            'details' => $details
        ]);
    }
}
