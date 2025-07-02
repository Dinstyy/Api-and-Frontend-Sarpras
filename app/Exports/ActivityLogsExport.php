<?php

namespace App\Exports;

use App\Models\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityLogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ActivityLog::with('user')->get()->map(function ($log) {
            return [
                'ID' => $log->id,
                'Username' => $log->user?->username ?? '-',
                'Aksi' => $log->action,
                'Deskripsi' => $log->description,
                'IP Address' => $log->ip_address,
                'User Agent' => $log->user_agent,
                'Waktu' => $log->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Username', 'Aksi', 'Deskripsi', 'IP Address', 'User Agent', 'Waktu'];
    }
}
