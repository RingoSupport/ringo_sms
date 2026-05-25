<?php

namespace App\Support;

use App\Models\AuditLog;

class Audit
{
    public static function log(
        string $action,
        ?string $description = null,
        $target = null
    ): void {

      AuditLog::create([
    'user_id' => auth()->id(),

    'action' => $action,

    'target_type' => $target
        ? class_basename($target)
        : null,

    'target_id' => $target
        ? $target->id
        : null,

    'description' => $description,

    'ip_address' => request()->ip(),
]);
    }
}
