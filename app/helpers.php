<?php

use App\Models\ActivityLog;

if (!function_exists('logActivity')) {
    function logActivity($action, $description = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
