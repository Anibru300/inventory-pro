<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    protected static $auditableEvents = ['created', 'updated', 'deleted'];

    public static function bootAuditable()
    {
        static::created(function ($model) {
            if (in_array('created', static::$auditableEvents)) {
                AuditLog::log(
                    'create',
                    class_basename($model),
                    $model->id,
                    null,
                    $model->toArray()
                );
            }
        });

        static::updated(function ($model) {
            if (in_array('updated', static::$auditableEvents)) {
                AuditLog::log(
                    'update',
                    class_basename($model),
                    $model->id,
                    $model->getOriginal(),
                    $model->toArray()
                );
            }
        });

        static::deleted(function ($model) {
            if (in_array('deleted', static::$auditableEvents)) {
                AuditLog::log(
                    'delete',
                    class_basename($model),
                    $model->id,
                    $model->toArray(),
                    null
                );
            }
        });
    }
}
