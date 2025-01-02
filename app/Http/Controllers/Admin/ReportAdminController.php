<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportAdminController extends Controller
{
    public function index()
    {
        $reports = Report::with(['province', 'city', 'district'])->orderBy('created_at', 'desc')->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report = Report::with(['province', 'city', 'district'])->whereId($report->id)->first();
        return view('admin.reports.show', compact('report'));
    }

    public function approve(Report $report)
    {
        $this->authorize('approved', $report);

        $report->status = 'approved';
        $report->save();

        return redirect()->route('admin.reports.index')->with('success', 'Laporan telah disetujui.');
    }

    public function reject(Report $report, Request $request)
    {
        $this->authorize('rejected', $report);

        $request->validate([
            'reason' => 'required|string',
        ]);

        $report->status = 'rejected';
        $report->rejection_reason = $request->input('reason');
        $report->save();

        return redirect()->route('admin.reports.index')->with('success', 'Laporan telah ditolak.');
    }
}
