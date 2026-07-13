<?php

namespace App\Http\Controllers;

use App\Models\KegiatanAsatidz;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = KegiatanAsatidz::with(['asatidz.user', 'details.masterKegiatan']);

        if ($request->filled('asatidz_id')) {
            $query->where('asatidz_id', $request->asatidz_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_kegiatan', [$request->start_date, $request->end_date]);
        }

        $query->orderBy('tanggal_kegiatan', 'desc');
        
        $perPage = $request->input('per_page', 10);
        $laporan = $query->paginate($perPage);

        return response()->json($laporan);
    }

    public function export(Request $request)
    {
        $asatidzId = $request->input('asatidz_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $fileName = 'Laporan_Aktivitas_Asatidz_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new LaporanExport($asatidzId, $startDate, $endDate), $fileName);
    }

    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $asatidzId = $request->input('asatidz_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = KegiatanAsatidz::with(['asatidz.user', 'details.masterKegiatan']);

        if ($asatidzId) {
            $query->where('asatidz_id', $asatidzId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kegiatan', [$startDate, $endDate]);
        }

        $totalPoin = $query->sum('total_poin');
        $data = $query->orderBy('tanggal_kegiatan', 'desc')->cursor();
        $asatidz = $asatidzId ? \App\Models\Asatidz::find($asatidzId) : null;

        return view('pdf.laporan', [
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'asatidz' => $asatidz,
            'total_poin' => $totalPoin
        ]);
    }
}
