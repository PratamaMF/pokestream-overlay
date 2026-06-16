<?php

namespace App\Traits;

use App\Models\ActivityLog;

/**
 * Trait Loggable
 * * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void created(\Closure $callback)
 * @method static void updating(\Closure $callback)
 * @method static void deleted(\Closure $callback)
 */
trait Loggable
{
    protected static function bootLoggable()
    {
        self::created(function ($model) {
            $modelName = class_basename($model);
            
            $data = collect($model->getAttributes())
                ->except(['id', 'created_at', 'updated_at', 'password'])
                ->toArray();

            $details = [
                'action' => 'create',
                'data' => $data
            ];

            $identifier = $model->product_name ?? $model->category_name ?? $model->title ?? $model->name ?? 'New Item';

            ActivityLog::hasLog("Create {$modelName} : '{$identifier}'", $modelName, $details);
        });

        self::updating(function ($model) {
            $modelName = class_basename($model);
            $changes = [];

            foreach ($model->getDirty() as $key => $newValue) {
                if (in_array($key, ['updated_at', 'password', 'current_password'])) continue;

                $oldValue = $model->getOriginal($key);
                $fieldLabel = ucwords(str_replace('_', ' ', $key));

                $changes[$fieldLabel] = [
                    'old' => (string) $oldValue,
                    'new' => (string) $newValue
                ];
            }

            if (!empty($changes)) {
                $details = [
                    'action' => 'update',
                    'changes' => $changes
                ];

                $identifier = $model->getOriginal('product_name') ?? $model->getOriginal('category_name') ?? $model->getOriginal('title') ?? $model->getOriginal('name') ?? 'Item';

                ActivityLog::hasLog("Update detail data {$modelName}: '{$identifier}'", $modelName, $details);
            }
        });

        self::deleted(function ($model) {
            $modelName = class_basename($model);
            
            $data = collect($model->getAttributes())
                ->except(['id', 'created_at', 'updated_at', 'password'])
                ->toArray();

            $details = [
                'action' => 'delete',
                'data' => $data
            ];

            $identifier = $model->product_name ?? $model->category_name ?? $model->title ?? $model->name ?? 'Item';

            ActivityLog::hasLog("Delete data {$modelName} : '{$identifier}'", $modelName, $details);
        });
    }
}