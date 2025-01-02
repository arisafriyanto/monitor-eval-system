<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::with(['province', 'city', 'district'])->orderBy('created_at', 'desc')->get();
        // dd($reports);
        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::pluck('name', 'code');
        return view('reports.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $attr = $request->validate([
            'program_name' => 'required|string|max:255',
            'recipient_count' => 'required|integer|min:1',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            'district_code' => 'required|exists:indonesia_districts,code',
            'distribution_date' => 'required|date',
            'evidence_file' => 'required|file|mimes:jpg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('evidence_file');
        $uniqueFileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('reports', $uniqueFileName, 'public');

        $attr['evidence_file'] = $filePath;

        Report::create($attr);
        return redirect()->route('reports.index')->with('success', 'Berhasil buat laporan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $report = Report::with(['province', 'city', 'district'])->where('id', $report->id)->first();
        return view('reports.show', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        $provinces = Province::pluck('name', 'code');
        $cities = City::where('province_code', $report->province_code)->pluck('name', 'code');
        $districts = District::where('city_code', $report->city_code)->pluck('name', 'code');

        return view('reports.edit', compact('report', 'provinces', 'cities', 'districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $attr = $request->validate([
            'program_name' => 'required|string|max:255',
            'recipient_count' => 'required|integer|min:1',
            'province_code' => 'required|exists:indonesia_provinces,code',
            'city_code' => 'required|exists:indonesia_cities,code',
            'district_code' => 'required|exists:indonesia_districts,code',
            'distribution_date' => 'required|date',
            'evidence_file' => 'file|mimes:jpg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('evidence_file')) {
            if ($report->evidence_file && Storage::disk('public')->exists($report->evidence_file)) {
                Storage::disk('public')->delete($report->evidence_file);
            }

            $file = $request->file('evidence_file');
            $uniqueFileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('reports', $uniqueFileName, 'public');

            $attr['evidence_file'] = $filePath;
        }

        $report->update($attr);
        return redirect()->route('reports.index')->with('success', 'Berhasil edit laporan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);

        if ($report->evidence_file && Storage::disk('public')->exists($report->evidence_file)) {
            Storage::disk('public')->delete($report->evidence_file);
        }

        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Berhasil hapus laporan.');
    }
}
