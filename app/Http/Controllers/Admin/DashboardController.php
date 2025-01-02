<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();

        $recipientCountPerProgram = Report::select('program_name', DB::raw('SUM(recipient_count) as total_recipients'))
            ->where('status', 'approved')
            ->groupBy('program_name')
            ->get();

        $distributionPerRegion = Report::with(['province', 'city'])
            ->select('province_code', 'city_code', DB::raw('COUNT(*) as total_reports'))
            ->where('status', 'approved')
            ->groupBy('province_code', 'city_code')
            ->get();

        return view('admin.dashboard', compact('totalReports', 'recipientCountPerProgram', 'distributionPerRegion'));
    }
}
