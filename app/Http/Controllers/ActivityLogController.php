<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Exports\ActivityLogsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ActivityLogController extends Controller
{
    const PER_PAGE = 10;

    public function index()
    {
        $logs = ActivityLog::latest()->paginate(self::PER_PAGE);
        return view('activity-logs.index', compact('logs'));
    }

    public function exportExcel()
    {
        $fileName = 'activity-logs-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new ActivityLogsExport, $fileName);
    }

    public function exportPdf()
    {
        $logs = ActivityLog::latest()->get();
        $fileName = 'activity-logs-' . now()->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('activity_logs.pdf', compact('logs'));
        return $pdf->download($fileName);
    }
}
