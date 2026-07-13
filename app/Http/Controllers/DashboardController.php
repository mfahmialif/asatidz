<?php

namespace App\Http\Controllers;

use App\Models\Asatidz;
use App\Models\KegiatanAsatidz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        $now = Carbon::now();
        $today = $now->toDateString();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();
        $startOfYear = $now->copy()->startOfYear()->toDateString();
        $endOfYear = $now->copy()->endOfYear()->toDateString();

        $totalAsatidz = Asatidz::count();
        
        $getStats = function($start, $end) {
            $poin = KegiatanAsatidz::whereBetween('tanggal_kegiatan', [$start, $end])->sum('total_poin');
            $laporan = KegiatanAsatidz::whereBetween('tanggal_kegiatan', [$start, $end])->count();
            return ['poin' => $poin, 'laporan' => $laporan];
        };

        $hariIni = $getStats($today, $today);
        $bulanIni = $getStats($startOfMonth, $endOfMonth);
        $tahunIni = $getStats($startOfYear, $endOfYear);

        // Leaderboard (Top 10 bulan ini)
        $leaderboard = Asatidz::with('user')
            ->withSum(['kegiatan as total_poin_bulan_ini' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('tanggal_kegiatan', [$startOfMonth, $endOfMonth]);
            }], 'total_poin')
            ->orderByDesc('total_poin_bulan_ini')
            ->take(10)
            ->get();

        // Chart (7 hari terakhir)
        $dates = [];
        $chartPoints = [];
        $chartReports = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $dates[] = Carbon::parse($date)->format('d M');
            
            $chartPoints[] = KegiatanAsatidz::whereDate('tanggal_kegiatan', $date)->sum('total_poin');
            $chartReports[] = KegiatanAsatidz::whereDate('tanggal_kegiatan', $date)->count();
        }

        return response()->json([
            'widgets' => [
                'total_asatidz' => $totalAsatidz,
                'hari_ini' => [
                    'poin' => $hariIni['poin'],
                    'laporan' => $hariIni['laporan'],
                    'label' => $now->translatedFormat('d F Y')
                ],
                'bulan_ini' => [
                    'poin' => $bulanIni['poin'],
                    'laporan' => $bulanIni['laporan'],
                    'label' => $now->translatedFormat('F Y')
                ],
                'tahun_ini' => [
                    'poin' => $tahunIni['poin'],
                    'laporan' => $tahunIni['laporan'],
                    'label' => 'Tahun ' . $now->format('Y')
                ],
            ],
            'leaderboard' => $leaderboard,
            'chart' => [
                'labels' => $dates,
                'points' => $chartPoints,
                'reports' => $chartReports
            ]
        ]);
    }

    public function asatidz()
    {
        $user = Auth::user();
        if (!$user->asatidz) {
            return response()->json(['message' => 'Not an asatidz'], 403);
        }
        
        $asatidzId = $user->asatidz->id;
        
        // Define ranges
        $now = Carbon::now();
        $today = $now->toDateString();
        
        $startOfWeek = $now->copy()->startOfWeek()->toDateString();
        $endOfWeek = $now->copy()->endOfWeek()->toDateString();
        
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();
        
        $startOfYear = $now->copy()->startOfYear()->toDateString();
        $endOfYear = $now->copy()->endOfYear()->toDateString();

        // Helper to get stats
        $getStats = function($start, $end) use ($asatidzId) {
            $poin = KegiatanAsatidz::where('asatidz_id', $asatidzId)
                ->whereBetween('tanggal_kegiatan', [$start, $end])
                ->sum('total_poin');
            $laporan = KegiatanAsatidz::where('asatidz_id', $asatidzId)
                ->whereBetween('tanggal_kegiatan', [$start, $end])
                ->count();
            return ['poin' => $poin, 'laporan' => $laporan];
        };

        $hariIni = $getStats($today, $today);
        $mingguIni = $getStats($startOfWeek, $endOfWeek);
        $bulanIni = $getStats($startOfMonth, $endOfMonth);
        $tahunIni = $getStats($startOfYear, $endOfYear);

        // Chart (7 hari terakhir)
        $dates = [];
        $chartPoints = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $dates[] = Carbon::parse($date)->format('d M');
            
            $chartPoints[] = KegiatanAsatidz::where('asatidz_id', $asatidzId)
                ->whereDate('tanggal_kegiatan', $date)
                ->sum('total_poin');
        }

        return response()->json([
            'widgets' => [
                'hari_ini' => [
                    'poin' => $hariIni['poin'],
                    'laporan' => $hariIni['laporan'],
                    'label' => $now->translatedFormat('d F Y')
                ],
                'minggu_ini' => [
                    'poin' => $mingguIni['poin'],
                    'laporan' => $mingguIni['laporan'],
                    'label' => Carbon::parse($startOfWeek)->translatedFormat('d M') . ' - ' . Carbon::parse($endOfWeek)->translatedFormat('d M Y')
                ],
                'bulan_ini' => [
                    'poin' => $bulanIni['poin'],
                    'laporan' => $bulanIni['laporan'],
                    'label' => $now->translatedFormat('F Y')
                ],
                'tahun_ini' => [
                    'poin' => $tahunIni['poin'],
                    'laporan' => $tahunIni['laporan'],
                    'label' => 'Tahun ' . $now->format('Y')
                ],
            ],
            'chart' => [
                'labels' => $dates,
                'points' => $chartPoints
            ]
        ]);
    }
}
